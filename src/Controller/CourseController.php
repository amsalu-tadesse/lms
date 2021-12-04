<?php

namespace App\Controller;

use App\Controller\UtilityController;
use App\Entity\Course;
use App\Entity\InstructorCourse;
use App\Entity\InstructorCourseStatus;
use App\Entity\QuestionAnswer;
use App\Form\CourseType;
use App\Form\QuestionAnswerNewStudentType;
use App\Repository\ContentRepository;
use App\Repository\CourseRepository;
use App\Repository\InstructorCourseChapterRepository;
use App\Repository\InstructorCourseRepository;
use App\Repository\StudentQuizRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;


/**
 * @Route("/course")
 */
class CourseController extends AbstractController
{
    /**
     * @Route("/", name="course_index", methods={"GET","POST"})
     */
    public function index(CourseRepository $courseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // $this->denyAccessUnlessGranted('course_list');
        $em = $this->getDoctrine()->getManager();
        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $course = $courseRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(CourseType::class, $course);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //$instructorCourse = $em->getRepository(InstructorCourse::class)->findOneBy(['course'=>$course,'active'=>1]);//assumption only one inst for one active inst course.
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('course_index');
            }

            $queryBuilder = $courseRepository->findCourse($request->query->get('search'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('course/index.html.twig', [
                'courses' => $data,
                'form' => $form->createView(),
                'edit' => $id,
            ]);
        }
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setStatus(1);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($course);
            $entityManager->flush();
            $instructorCourse = new InstructorCourse();
            $instructorCourse->setCourse($course);
            $instructorCourseStatus = $em->getRepository(InstructorCourseStatus::class)->find(1); //not assigned
            $instructorCourse->setStatus($instructorCourseStatus);
            $instructorCourse->setCreatedAt(new DateTime());
            $instructorCourse->setActive(true);
            $entityManager->persist($instructorCourse);
            $entityManager->flush();

            return $this->redirectToRoute('course_index');
        }

        $queryBuilder = $courseRepository->findCourse($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('course/index.html.twig', [
            'courses' => $data,
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="course_description")
     */
    public function courseDetail(InstructorCourse $instructorCourse, InstructorCourseRepository $course_repo, ContentRepository $content, Request $request): Response
    {
        $courses = $course_repo->findCoursesSortByCategory($instructorCourse->getId());
        $chaptersWithContent = $content->getChaptersWithContentForCourse($instructorCourse->getId());
        $questionAnswer = new QuestionAnswer();
        $form = $this->createForm(QuestionAnswerNewStudentType::class, $questionAnswer);
        $form->handleRequest($request);

        if ($this->isGranted("ROLE_STUDENT")) {
            if ($form->isSubmitted() && $form->isValid()) {
                $questionAnswer->setStudent($this->getUser()->getProfile());
                $questionAnswer->setQuestion($form['question']->getData());
                $questionAnswer->setCreatedAt(new DateTime());
                $questionAnswer->setNotification(0);
                $questionAnswer->setIsReply(0);
                $questionAnswer->setCourse($course_repo->find($request->request->get("val1")));
                $em = $this->getDoctrine()->getManager();
                $em->persist($questionAnswer);
                $em->flush();
            }

            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository(QuestionAnswer::class)->findBy(['course'=>$instructorCourse->getId()], ['id'=>'desc']);
            return $this->render('course/description_login.html.twig', [
                'chapter' => $courses,
                'chapters' => $chaptersWithContent,
                'question' => $question,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('course/description.html.twig', [
            'chapter' => $courses,
            'question' => [],
            'form' => $form->createView(),
            'chapters' => $chaptersWithContent,
        ]);
    }

    /**
     * @Route("/mycourses", name="selected_courses")
     */
    public function selectedCourses(Request $request, InstructorCourseRepository $course): Response
    {
        if ($this->isGranted("ROLE_STUDENT")) {
            $selected_courses = $request->cookies->get("selected_courses_login");
            $selected_courses = json_decode($selected_courses, true);

            $em = $this->getDoctrine()->getManager();
            $courses = $em->getRepository(InstructorCourse::class)->findBy(array('id' => $selected_courses));
            return $this->render('student_course/selected_courses_login.html.twig', [
                'courses' => $courses,
            ]);
        }

        $selected_courses = $request->cookies->get("selected_courses");
        $selected_courses = json_decode($selected_courses, true);

        //dd($selected_courses);
        $em = $this->getDoctrine()->getManager();
        $courses = $em->getRepository(InstructorCourse::class)->findBy(array('id' => $selected_courses));
        return $this->render('course/selected_courses.html.twig', [
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/remove", name="remove_selected")
     */
    public function removeSelected()
    {
        $response = new Response();
        $response->headers->clearCookie('selected_courses');
        $response->send();
        dd("done");
    }

    /**
     * @Route("/{id}/chapters/", name="course_chapters", methods={"GET"})
     */
    public function chapters(InstructorCourse $course, StudentQuizRepository $student_quiz, ContentRepository $contentRepository, InstructorCourseChapterRepository $chaptersRepository): Response
    {
        // $this->denyAccessUnlessGranted('chapter_list');
        $chapters = $chaptersRepository->findChapters($course->getId(), $this->getUser()->getProfile()->getId());
        $contents = $contentRepository->getContentsCount($course->getId());
        $chapter_list = array();

        foreach ($chapters as $key => $value) {
            $flag = 1;
            $chapter_list[$key] = $value[0];
            foreach ($contents as $key1 => $value1) {
                if ($value[0]['id'] == $value1['id']) {
                    // $chapters[$key] = $value
                    $chapter_list[$key]['total_video'] = $value1['total_video'];
                    $chapter_list[$key]['total_content'] = $value1['total_content'];
                    $chapter_list[$key]['completed'] = ($value['pagesCompleted'] / $value1['total_content']) * 100;
                    $flag = 2;
                    break;
                }
            }

            if ($flag == 1) {
                $chapter_list[$key]['total_video'] = 0;
                $chapter_list[$key]['total_content'] = 0;
                $chapter_list[$key]['completed'] = 0;
            }
        }

        $quiz = $student_quiz->getQuizesForStudent($course->getId(), $this->getUser()->getProfile()->getId());
        $quizWithChapter = array();
        foreach ($quiz as $key => $value) {
            $quizWithChapter[$value['chapter_id']] = $value;
        }
        return $this->render('student_course/chapters.html.twig', [
            'chapters' => $chapter_list,
            'instructorCourse' => $course,
            'contents' => $contents,
            'quiz' => $quizWithChapter,
        ]);
    }

    /**
     * @Route("/{id}", name="course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course): Response
    {
        // $this->denyAccessUnlessGranted('course_delete');
        if ($this->isCsrfTokenValid('delete' . $course->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $entityManager->remove($course);
                $entityManager->flush();
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('course_index');
    }

    /**
     * @Route("/export", name="course_export")
     */
    public function courseListToExcel(CourseRepository $course_repo)
    {
        return $this->render('report/course.html.twig',[
            'courses' => $course_repo->findAll()
        ]);
    }

    /**
     * @Route("/export/detail", name="specific_course_export", methods={"POST"})
     */
    public function courseToPdf(CourseRepository $course_repo, ContentRepository $contentRepository, Request $request, InstructorCourseRepository $inst_course_repo)
    {
        $req = $request->request->get('coursePdfExport');
        if($request->request->get("course_selected"))
            $course = $course_repo->find($request->request->get("course_selected"));
        if($course)
        {
            $contents = $contentRepository->getChaptersWithContentForCourse1($course->getId());
            $instructor_info = array();
            $total_student = $content_view = 0;
            if($req)
            {
                if(in_array('stu_num',$req))
                {
                    $total_student = $course_repo->findTotalActiveStudent($course->getId());
                    if($total_student)
                        $total_student = $total_student['total_student'];
                    else
                        $total_student = 0;
                }
                
                if(in_array('con_lis',$req))
                {
                    $content_view = 1;
                }
                if(in_array('ins_inf',$req))
                {
                    $instructor_info = $inst_course_repo->findBy(array('course'=> $course->getId()),array('id'=>'DESC'),1,0);
                    if($instructor_info) $instructor_info = $instructor_info[0];
                }
            }

            // Configure Dompdf according to your needs
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFont', 'Arial');
            
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf($pdfOptions);
            // Retrieve the HTML generated in our twig file
            $html = $this->renderView('report/coursePrintTemplate.html.twig', [
                'course' => $course,
                'total_student' => $total_student,
                'instructor_info' => $instructor_info,
                'content_view' =>  $content_view,
                'contents' => $contents
            ]);
        
            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            
            // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();
            
            $course_name = $course->getName()."-report";
            // Output the generated PDF to Browser (force download)
            $dompdf->stream("$course_name", [
                "Attachment" => true
            ]);
        }

        return $this->render('report/course.html.twig',[
            'courses' => $course_repo->findAll()
        ]);
    }


    /**
     * @Route("/export/excel", name="course_list_export", methods={"POST","GET"})
     */
    public function courseExport(CourseRepository $course_repo, Request $request)
    {
        $req = $request->request->get('courseExport');
        $courses = $course_repo->findAll();
        $students = $instructors = $finished = $unfinished = $chapter_number = array();
        if($req){
            $em = $this->getDoctrine()->getManager();
            if(in_array("stu_num", $req)){
                $items = $course_repo->courseWithStudentNumber();
                $query = $em->createQuery($items);
                $students = $query->getResult();
                if($students) $students = $this->changeArrayFormat($students);
            }
            if(in_array("ins_num", $req)){
                $instructors = $course_repo->courseWithInstructorNumber();
                if($instructors) $instructors = $this->changeArrayFormat($instructors);
            }
            if(in_array("fin_stud_num", $req)){
                $items = $course_repo->courseWithStudentNumber(5);
                $query = $em->createQuery($items);
                $finished = $query->getResult();
                if($finished) $finished = $this->changeArrayFormat($finished);
            }
            if(in_array("unf_stu_num", $req)){
                $items = $course_repo->courseWithStudentNumber(1);
                $query = $em->createQuery($items);
                $unfinished = $query->getResult();
                if($unfinished) $unfinished = $this->changeArrayFormat($unfinished);
            }
            if(in_array("cha_num", $req)){
                $chapter_number = $course_repo->getChaptersCount();
                if($chapter_number) $chapter_number = $this->changeArrayFormat($chapter_number);
            }
        }
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);

        $Excel_writer = new Xlsx($spreadsheet);

        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->setCellValue('A1', 'Course Name');
        $activeSheet->setCellValue('B1', 'Course Code');
        $activeSheet->setCellValue('C1', 'Course Category');

        $startLetter = 'D';
        if($students) $activeSheet->setCellValue($startLetter++."1",'Number of Students');
        if($finished) $activeSheet->setCellValue($startLetter++."1",'Students complete the course');
        if($unfinished) $activeSheet->setCellValue($startLetter++."1",'Students not complete the course');
        if($instructors) $activeSheet->setCellValue($startLetter++."1",'Number of Instructors');
        if($chapter_number) $activeSheet->setCellValue($startLetter++."1",'Number of Chapters');

        if($courses) {
            $i = 2;
            foreach($courses as $course){
                $activeSheet->setCellValue('A'.$i , $course->getName());
                $activeSheet->setCellValue('B'.$i , $course->getCode());
                $activeSheet->setCellValue('C'.$i , $course->getCategory()->getName());
                $startLetter = 'D';
                if($students) 
                    if(array_key_exists($course->getId(), $students))
                        $activeSheet->setCellValue($startLetter++.$i, $students[$course->getId()]);
                    else
                    $activeSheet->setCellValue($startLetter++.$i, 0);
                if($finished) 
                    if(array_key_exists($course->getId(), $finished))
                        $activeSheet->setCellValue($startLetter++.$i, $finished[$course->getId()]);
                    else
                        $activeSheet->setCellValue($startLetter++.$i, 0);

                if($unfinished) 
                    if(array_key_exists($course->getId(), $unfinished))
                        $activeSheet->setCellValue($startLetter++.$i, $unfinished[$course->getId()]);
                    else
                        $activeSheet->setCellValue($startLetter++.$i, 0);
                if($instructors) 
                    if(array_key_exists($course->getId(), $instructors))
                        $activeSheet->setCellValue($startLetter++.$i, $instructors[$course->getId()]); 
                    else
                        $activeSheet->setCellValue($startLetter++.$i, 0); 
                if($chapter_number) 
                    if(array_key_exists($course->getId(), $chapter_number))
                        $activeSheet->setCellValue($startLetter++.$i, $chapter_number[$course->getId()]); 
                    else
                        $activeSheet->setCellValue($startLetter++.$i, 0);       
                $i++;
            }
        
            $spreadsheet->getActiveSheet()->getStyle('A1:J1')->getAlignment()->setWrapText(true);
            // $spreadsheet->getActiveSheet()->getStyle('A1:A500')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle("A1:J1")->getFont()->setBold(true);

            $filename = 'Course-report_'.date("d",time())."_".date("m", time())."_".date("y", time()).'.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename='. $filename);
            header('Cache-Control: max-age=0');
            $Excel_writer->save('php://output');
            exit();
        }   

        return $this->redirectToRoute("course_export");
    }

    function changeArrayFormat($array)
    {
        $result = array();
        if($array){
            foreach($array as $key => $value)
            {
                $keys = array_keys($value);
                $result[$value[$keys[0]]] = $value[$keys[1]];
            }
        }
        else{
            $result = [];
        }
        return $result;
    }
}
