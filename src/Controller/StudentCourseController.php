<?php

namespace App\Controller;

use App\Entity\InstructorCourse;
use App\Entity\StudentCourse;
use App\Entity\Student;
use App\Form\Filter\RequestFilterType;
use App\Form\StudentCourseType;
use App\Form\StudentReportType;
use App\Repository\InstructorCourseChapterRepository;
use App\Repository\CourseRepository;
use App\Repository\InstructorCourseRepository;
use App\Repository\StudentChapterRepository;
use App\Repository\StudentCourseRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Route("/student")
 */
class StudentCourseController extends AbstractController
{
    /**
     * @Route("/", name="student_course_index", methods={"GET"})
     */
    public function index(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request, InstructorCourseRepository $course): Response
    { 
        if ($this->getUser()->getProfile() == null) {
            return $this->redirectToRoute('app_login');
        }

        $queryBuilder = $studentCourseRepository->findCourses($this->getUser()->getProfile()->getId());
        $courses = $course->findCoursesSortByCategory();

        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/student.html.twig', [
            'student_courses' => $data,
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/course/request", name="course_request")
     */
    public function courseRequest(Request $request, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator): Response
    {
        if ($this->isGranted("ROLE_ADMIN") || $this->isGranted("ROLE_DIRECTOR")) {
            $studentCourseRepository->updateNotification();
        }
        $st_course = new StudentCourse();
        $searchForm = $this->createForm(RequestFilterType::class, $st_course);
        $searchForm->handleRequest($request);

        $queryBuilder = $studentCourseRepository->findRequests($request->query->get('student'), $request->query->get('instructorCourse'));
        $stdCrs = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('student_course/course_requests.html.twig', [
            'student_courses' => $stdCrs,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/course/request/approved", name="approved_course_request", methods={"GET"})
     */
    public function approvedCourseRequest(Request $request, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator): Response
    {
        $st_course = new StudentCourse();
        $searchForm = $this->createForm(RequestFilterType::class, $st_course);
        $searchForm->handleRequest($request);

        $queryBuilder = $studentCourseRepository->findRequestsApproved($request->query->get('student'), $request->query->get('instructorCourse'));
        $stdCrs = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('student_course/approved_course_requests.html.twig', [
            'student_courses' => $stdCrs,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/course/student/finished", name="finished_students_list", methods={"GET"})
    */
    public function finishedCourses(StudentCourseRepository $stud_course_repo, PaginatorInterface $paginator, Request $request)
    {
        $queryBuilder = $stud_course_repo->findBy(['status'=>5], ['id'=>'DESC']);

        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('student_course/certification.html.twig', [
            'student_courses' => $data,
        ]);
    }
    
    /**
     * @Route("/list/export", name="student_course_excel", methods={"GET", "POST"})
    */
    public function exportToExcel(CourseRepository $course_repo, Request $request, InstructorCourseRepository $instructorCourseRepository, PaginatorInterface $paginator, StudentCourseRepository $stud_course_repo)
    {
        $em = $this->getDoctrine()->getManager();
        $students = array();
        $instructors = array();
        $inst = 0;
        $studentCourse = new StudentCourse();
        $form = $this->createForm(StudentReportType::class, $studentCourse);
        $form->handleRequest($request);

        if($form->isSubmitted() )
        {
            $course = $form['courses']->getData();
            $status = $form['status']->getData();
            $sex = $form['sex']->getData();
            $inst = $request->request->get("instructor");
            if($status == 0) $status = 1;
            else if($status == 1) $status = 5;
            else if($status == 2) $status = 0;

            if(!$inst) $inst = 0; 
            if($course)
            {
                $students = $stud_course_repo->getStudents($course->getId(), $status, $inst, $sex);
            }

            if($form['export']->isClicked()){
                $spreadsheet = new Spreadsheet();
                $spreadsheet->setActiveSheetIndex(0);

                $Excel_writer = new Xlsx($spreadsheet);

                $activeSheet = $spreadsheet->getActiveSheet();

                $activeSheet->setCellValue('A1', 'Student Name');
                $activeSheet->setCellValue('B1', 'Student Email');
                $activeSheet->setCellValue('C1', 'Course Name');
                $activeSheet->setCellValue('D1', 'Course Category');
                $activeSheet->setCellValue('E1', 'Instructor');
                $activeSheet->setCellValue('F1', 'Status');
                $i = 2;
                foreach($students as $student){
                    $name = $student->getStudent()->getUser()->getFirstName()." ".$student->getStudent()->getUser()->getMiddleName()." ".$student->getStudent()->getUser()->getLastName();
                    $instructorName = $student->getInstructorCourse()->getInstructor()->getUser()->getFirstName()." ".$student->getInstructorCourse()->getInstructor()->getUser()->getMiddleName()." ".$student->getInstructorCourse()->getInstructor()->getUser()->getLastName();
                    $activeSheet->setCellValue('A'.$i , $name);
                    $activeSheet->setCellValue('B'.$i , $student->getStudent()->getUser()->getEmail());
                    $activeSheet->setCellValue('C'.$i , $student->getInstructorCourse()->getCourse()->getName());
                    $activeSheet->setCellValue('D'.$i , $course->getCategory()->getName());
                    $activeSheet->setCellValue('E'.$i , $instructorName);
                    if($student->getStatus() == 1)
                        $status = 'Finished';
                    elseif($student->getStatus() == 5)
                        $status = 'Not Finished';
                    $activeSheet->setCellValue('F'.$i , $status);

                    $i++;
                }

                $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setWrapText(true);
                // $spreadsheet->getActiveSheet()->getStyle('A1:A500')->getAlignment()->setWrapText(true);

                $spreadsheet->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);

                $filename = 'student_list_'.date("d",time())."_".date("m", time())."_".date("y", time()).'.xlsx';
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename='. $filename);
                header('Cache-Control: max-age=0');
                $Excel_writer->save('php://output');
                exit();
            }
            else
            {                
                $instructors = $instructorCourseRepository->getInstructorsForCourse($course->getId());
            }
        }

        $data = $paginator->paginate(
            $students,
            $request->query->getInt('page', 1),
            15
        );
        
        return $this->render('report/student.html.twig',[
            'courses' => $course_repo->findAll(),
            'students' => $data,
            'instructors' => $instructors,
            'inst' => $inst,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/course/certificate/{id}/print", name="print_certificate", methods={"GET"})
    */
    public function printCertificate(StudentCourse $studentCourse)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository(Student::class)->find($studentCourse->getStudent()->getId());
        if($student)
        {
            if($student->getProfileUpdated() && $student->getProfilePicture()){
                return $this->render('certificate/print.html.twig', [
                    'student_course' => $studentCourse,
                ]);
            }
            else{
                $student_id = $student->getStudentId();
                $this->addFlash("info", "$student_id does not updated his/her profile");
                return $this->redirectToRoute("finished_students_list");
            }
        }
        else{
            return $this->redirectToRoute("finished_students_list");
        }        
    }

    /**
     * @Route("/course/requests/rejected", name="rejected_course_request", methods={"GET"})
     */
    public function rejectedCourseRequest(Request $request, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator): Response
    {
        $st_course = new StudentCourse();
        $searchForm = $this->createForm(RequestFilterType::class, $st_course);
        $searchForm->handleRequest($request);

        $queryBuilder = $studentCourseRepository->findRequestsRejected($request->query->get('student'), $request->query->get('instructorCourse'));
        $stdCrs = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('student_course/rejected_course_requests.html.twig', [
            'student_courses' => $stdCrs,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/course/request/{id}", name="course_request_activate", methods={"GET","POST"})
     */
    public function courseRequestActivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $studentCourse->setStatus(1); //accepted
        $em->flush();

        return $this->redirectToRoute('course_request');
    }

    /**
     * @Route("/course/drequest/{id}", name="course_request_deactivate", methods={"GET","POST"})
     */
    public function courseRequestDeactivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $studentCourse->setStatus(2); //accepted
        $em->flush();

        return $this->redirectToRoute('course_request');
    }

    /**
     * @Route("/course/{id}", name="students_in_course", methods={"GET"})
     */

    public function listStudentUnderCourse(StudentCourseRepository $studentCourseRepository, InstructorCourse $instructorCourse, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $studentCourseRepository->findBy(['instructorCourse' => $instructorCourse]);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $completion = [
        '0%'=>0,
        '1-24%'=>0,
        '25-49%'=>0,
        '50-74%'=>0,
        '75-99%'=>0,
        '100%'=>0,
        ];



        $json = "[['Student', 'Completion']";
        foreach ($queryBuilder as $studentCourse) {
            $stdid = $studentCourse->getId();

            $comp = $this->getCompletion($stdid);

            if ($comp==0) {
                $completion['0%'] ++;
            } elseif ($comp < 25) {
                $completion['1-24%'] ++;
            } elseif ($comp < 50) {
                # code...
                $completion['25-49%'] ++;
            } elseif ($comp < 75) {
                # code...
                $completion['50-74%'] ++;
            } elseif ($comp < 100) {
                # code...
                $completion['75-99%'] ++;
            } elseif ($comp =100) {
                # code...
                $completion['100%'] ++;
            } else {
                # code...
            }
        }


        foreach ($completion as $key=>$value) {
            $col =  ",['".$key."', ".$value."]";
            $col =trim(preg_replace('/\s+/', ' ', $col));
            $json .= $col ;
        }


        $json .= "];";



        return $this->render(
            'student_course/students_in_course.html.twig',
            [
                'student_courses' => $data,
                'instructor_course' => $instructorCourse,
                'completion'=>$json,
            ]
        );
    }

    public function getCompletion($stdid)
    {
        $totalContents = 0;
        $readContents = 0;

        $em = $this->getDoctrine()->getManager();
        $studentCourse = $em->getRepository(StudentCourse::class)->find($stdid);

        $chapters = $studentCourse->getInstructorCourse()->getInstructorCourseChapters();
        foreach ($chapters as $chapter) {
            $totalContents += sizeof($chapter->getContents());
        }
        
        $studentchapters = $studentCourse->getStudent()->getStudentChapters();

        foreach ($studentchapters as $stdchapter) 
        {
            $readContents += $stdchapter->getPagesCompleted();
        }
        
        $contents = $totalContents ? $totalContents:1;
        
        $completion = \round(($readContents/$contents), 1)*100;
        return $completion;
    }

    /**
     * @Route("/listProduct", name="list_student_in_course", methods={"GET","POST"})
     */
    public function listDatatablesAction(Request $request, StudentCourseRepository $studentCourseRepository)
    {
        // Set up required variables
        $this->entityManager = $this->getDoctrine()->getManager();

        // Get the parameters from DataTable Ajax Call
        if ($request->getMethod() == 'POST') {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');
            $instcrs = $request->request->get('id');
        } else { // If the request is not a POST one, die hard
            die;
        }

        // Get results from the Repository
        $results = $studentCourseRepository->getRequiredDTData($start, $length, $orders, $search, $columns, $instcrs);
        // Returned objects are of type Town
        $objects = $results["results"];
        // dd($objects);
        // Get total number of objects
        $total_objects_count = $studentCourseRepository->count(1);

        // Get total number of results
        $selected_objects_count = count($objects);

        // Get total number of filtered data
        $filtered_objects_count = $results["countResult"];

        $Response = array();
        $temp = array();
        foreach ($objects as $key => $value) {

            // dd($this->getCompletion($value['id']));
            $temp["id"] = $value['id'];
            $temp["name"] = $value["name"];
            $temp["page"] = $this->getCompletion($value['id']);
            $temp["createdAt"] = $value['createdAt']->format('Y-m-d');
            $icon = $value['active'] ? "fa-check-circle" : "fa-times-circle";
            $color = $value['active'] ? ' green' : ' red';

            $temp["status"] = '<a href="#" data-toggle="modal" id="' . $value['id'] . '" onclick="changeStatus(\'' . $value['name'] . '\',' . $value['id'] . ',' . $value['active'] . ')" data-target="#modal-delete">' .
                "<i class='fas $icon' style='color:$color'></i></a>";
            $temp["actions"] = '<a href="/student/' . $value['student'] . '" class="btn btn-primary btn-sm">show</a>';
            $Response[] = $temp;

            unset($temp);
            $temp = array();
        }
        // Construct response
        $response = '{
            "draw": ' . $draw . ',
            "recordsTotal": ' . $total_objects_count . ',
            "recordsFiltered": ' . $filtered_objects_count . ',
            "data":' . json_encode($Response) . '  }';

        // Send all this stuff back to DataTables
        $returnResponse = new JsonResponse();
        $returnResponse->setJson($response);

        return $returnResponse;
    }

    /**
     * @Route("/apply", name="course_apply")
     */
    public function apply(Request $request): Response
    {
        $courses = $request->cookies->get("selected_courses_login");
        $courses = json_decode($courses, true);
        $em = $this->getDoctrine()->getManager();
        foreach ($courses as $course) {
            $st_course = new StudentCourse();
            $st_course->setStudent($this->getUser()->getProfile());
            $st_course->setInstructorCourse($em->getRepository(InstructorCourse::class)->find($course));
            $st_course->setStatus(0);
            $st_course->setActive(0);
            $st_course->setDirectorNotification(0);
            $st_course->setTeacherNotification(0);
            $st_course->setCreatedAt(new DateTime());
            $em->persist($st_course);
            $em->flush();
        }
        //write form data to cookie
        $response = new Response();
        $cookie = new Cookie('selected_courses_login', "", time() * 60 * 60);
        $response->headers->setCookie($cookie);
        $response->sendHeaders();

        return $this->redirectToRoute("requested_courses");
    }

    /**
     * @Route("/request", name="requested_courses", methods={"GET"})
     */
    public function requests(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $studentCourseRepository->findRequestedCourses($this->getUser()->getProfile()->getId());

        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            15
        );

        return $this->render('student_course/requested_courses.html.twig', [
            'requests' => $data,
        ]);
    }

    /**
    * @Route("/approveMultiple", name="approve_multiple", methods={"GET","POST"})
    */
    public function requestApproveMultiple(StudentCourseRepository $stud_cour_repo, PaginatorInterface $paginator, Request $request): Response
    {
        $ids = array();
        $ids = explode(",", $request->request->get('checked_list')[0]);
        $em = $this->getDoctrine()->getManager();
        foreach ($ids as $id) {
            $stud_course = $stud_cour_repo->find($id);
            if ($stud_course != null) {
                $stud_course->setStatus(1);
                $em->persist($stud_course);
                $em->flush();
            }
        }
        return $this->redirectToRoute("course_request");
    }

    /**
     * @Route("/rejectMultiple", name="reject_multiple", methods={"GET","POST"})
     */
    public function requestRejectMultiple(StudentCourseRepository $stud_cour_repo, PaginatorInterface $paginator, Request $request): Response
    {
        $ids = array();
        $ids = explode(",", $request->request->get('checked_list')[0]);
        $em = $this->getDoctrine()->getManager();

        foreach ($ids as $key => $value) {
            $stud_course = $stud_cour_repo->find($value);
            if ($stud_course != null) {
                $stud_course->setStatus(2);
                $em->persist($stud_course);
                $em->flush();
            }
        }
        return $this->redirectToRoute("course_request");
    }

    /**
     * @Route("/student/course/status", name="change_student_course_status", methods={"GET"})
     */
    public function studentCourseActivate(StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $studentCourse = $studentCourseRepository->find($request->query->get("id"));
        $status = $studentCourse->getActive() == false ? 1 : 0;
        $studentCourse->setActive($status); //accepted
        $em->persist($studentCourse);
        $em->flush();

        $returnResponse = new JsonResponse();
        $returnResponse->setJson($status);

        return $returnResponse; // return $this->redirectToRoute('course_request');
    }
    /**
     * @Route("/udpate/{chapter}/counter", name="update_counter")
     */
    public function updater($chapter, StudentChapterRepository $student_chapter)
    {
        $em = $this->getDoctrine()->getManager();
        $stud_chap = $student_chapter->getProgress1($chapter, $this->getUser()->getProfile()->getId());
        $conn = $this->getDoctrine()->getManager()
            ->getConnection();
        $sql = "update student_chapter set pages_completed = pages_completed+1 where student_id = :student and id = :chapter";
        $stmt = $conn->prepare($sql);

        $stmt->execute(array("student" => $this->getUser()->getProfile()->getId(), "chapter" => $chapter));
        $returnResponse = new JsonResponse();
        $returnResponse->setJson("success");

        return $returnResponse;
    }

    /**
     * @Route("/request/{id}", name="request_show", methods={"GET"})
     */
    public function requestShow($id, StudentCourseRepository $studentCourseRepository, InstructorCourseChapterRepository $chapter): Response
    {
        $request = $studentCourseRepository->findRequest($this->getUser()->getProfile()->getId(), $id);

        $chaptersWithContent = $chapter->getChaptersWithContentForCourse($request['instructor_course_id']);

        return $this->render('student_course/request_show.html.twig', [
            'courses' => $request,
            'chapters' => $chaptersWithContent,
        ]);
    }

    /**
     * @Route("/course/diactivate/{id}", name="student_course_deactivate", methods={"GET","POST"})
     */
    public function studentCourseDeactivate(StudentCourse $studentCourse, StudentCourseRepository $studentCourseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($studentCourse->getActive()) {
            $studentCourse->setActive(false);
        } else {
            $studentCourse->setActive(true);
        }
        $em->flush();

        $queryBuilder = $studentCourseRepository->findBy(['instructorCourse' => $studentCourse->getInstructorCourse()]);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('student_course/students_in_course.html.twig', [
            'student_courses' => $data,
            'instructor_course' => $studentCourse->getInstructorCourse(),
        ]);
    }

    /**
     * @Route("/new", name="student_course_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $studentCourse = new StudentCourse();
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $studentCourse->setTeacherNotification(0);
            $studentCourse->setDirectorNotification(0);
            $entityManager->persist($studentCourse);
            $entityManager->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/new.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_show", methods={"GET"})
     */
    public function show(Student $student, StudentCourseRepository $stud_cour_repo): Response
    {
        $student_course = $stud_cour_repo->findBy(array('student'=>$student->getId()));
        return $this->render('student_course/show.html.twig', [
            'student_course' => $student_course,
            'student' => $student
        ]);
    }

    /**
     *  @Route("/{id}/remove", name="request_delete")
     */
    public function removeRequest(StudentCourseRepository $studentCourse, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $studentCourse = $entityManager->getRepository(StudentCourse::class)->find($id);
        $entityManager->remove($studentCourse);
        $entityManager->flush();

        return $this->redirectToRoute("requested_courses");
    }

    // /**
    //  * @Route("/{id}/remove", name="request_delete")
    //  */
    // public function deleteRequest(StudentCourseRepository $studentCourse, $id): Response
    // {
    //     $course = $studentCourse->find($id);
    //     $em = $this->getDoctrine()->getManager();
    //     $em->remove($course);
    //     $em->flush();

    //     // Send all this stuff back to DataTables
    //     $returnResponse = new JsonResponse();
    //     $returnResponse->setJson("kkd");

    //     return $returnResponse;
    // }

    /**
     * @Route("/home", methods={"GET"})
     */
    public function student()
    {
        return $this->render('student_course/student.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="student_course_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StudentCourse $studentCourse): Response
    {
        //dont use this section, it is not usefull.
        $form = $this->createForm(StudentCourseType::class, $studentCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student_course/edit.html.twig', [
            'student_course' => $studentCourse,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_course_delete", methods={"POST"})
     */
    public function delete(Request $request, StudentCourse $studentCourse): Response
    {
        if ($this->isCsrfTokenValid('delete' . $studentCourse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($studentCourse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_course_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/course/request/new/notification", name="new_course_request", methods={"GET"})
     */
    public function notification(StudentCourseRepository $stud_course_repo, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = array();
            $questions = $stud_course_repo->newCourseRequest();

            $returnResponse = new JsonResponse();
            $returnResponse->setJson($questions['new_requests']);

            return $returnResponse;
        } else {
            die("error");
        }
    }
}
