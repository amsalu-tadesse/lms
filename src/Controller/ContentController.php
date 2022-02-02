<?php

namespace App\Controller;

use App\Entity\Content;
use App\Entity\InstructorCourse;
use App\Entity\StudentChapter;
use App\Entity\SystemSetting;
use App\Entity\InstructorCourseChapter;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use App\Repository\InstructorCourseChapterRepository;
use App\Repository\StudentChapterRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Services\LogService;
use App\Repository\InstructorRepository;

/**
 * @Route("/content")
 */
class ContentController extends AbstractController
{
    /**
     * @Route("/mycourse/{id}", name="content_index", methods={"GET"})
     */
    public function index(ContentRepository $contentRepository, Request $request, InstructorCourse $instructorCourse, PaginatorInterface $paginator, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('content_list');

        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourse->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 

        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $content = $contentRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(ContentType::class, $content, array('incrsid' => $instructorCourse->getId()));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('content_index', ['id' => $instructorCourse->getId()]);
            }

            $queryBuilder = $contentRepository->findContent($request->query->get('search'), $instructorCourse);
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                10
            );

            return $this->render('content/index.html.twig', [
                'contents' => $data,
                'form' => $form->createView(),
                'edit' => $id,
                'incrsid' => $instructorCourse->getId(),
            ]);
        }

        $content = new Content();
        $em = $this->getDoctrine()->getManager();

        $uploadSize = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'upload_size'])->getValue();
        if (!$uploadSize) {
            $uploadSize = 100;
        } //default.
        $form = $this->createForm(ContentType::class, $content, array('uploadSize' => $uploadSize, 'incrsid' => $instructorCourse->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('content_index', ['id' => $instructorCourse->getId()]);
        }

        $queryBuilder = $contentRepository->findContent($request->query->get('search'), $instructorCourse);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('content/index.html.twig', [
            'contents' => $data,
            'form' => $form->createView(),
            'edit' => false,
            'incrsid' => $instructorCourse->getId(),
        ]);
    }

    /**
     * @Route("/lessons", name="studentview", methods={"GET"})
     */
    public function studentview(ContentRepository $contentRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $contentRepository->findContent($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('content/studentview.html.twig', [
            'contents' => $data,
            // 'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    /**
     * @Route("/studentlesson/page/{id}", name="studentlesson", methods={"GET","POST"})
     */
    public function studentviewtwo(InstructorCourse $instructorCourse, ContentRepository $contentRepository, Request $request, PaginatorInterface $paginator): Response
    {
        //$nextId = $request->get('id');
        //$data=$contentRepository->find($nextId);

        $queryBuilder = $contentRepository->findContent($request->query->get('search'), $instructorCourse);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            1
        );

        $fileName = $data->getItems()[0]->getFileName();
        $videoTypes = ["mp4", "avi", "ogg"];

        $isVideo = false;
        $fileExtenstion = '';
        if ($fileName) {
            $fnamToArr = explode(".", $fileName);
            $ext = end($fnamToArr);
            // dd($ext);

            $fileExtenstion = strtolower($ext);
            if (in_array($fileExtenstion, $videoTypes)) {
                $isVideo = true;
            }
        }

        return $this->render('content/studentviewtwo.html.twig', [
            'contents' => $data,
            'edit' => false,
            'isVideo' => $isVideo,
            'videoExtension' => $fileExtenstion,
            'uploadDir' => $this->getParameter('uploading_directory_resources'),
        ]);
    }

    /**
     * @Route("/{course}/{chapter}/list", name="content_list", methods={"GET"})
     */
    public function contentList($course, $chapter, InstructorCourseChapterRepository $course_chapter, StudentChapterRepository $stud_chap, ContentRepository $contentRepository, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $contents = $contentRepository->getContentsForChapter($course, $chapter);
        $final = false;
        $chapters = $em->getRepository(InstructorCourseChapter::class)->findBy(array("instructorCourse"=>$course), array('id'=>'DESC'),1,0);
        if($chapters){
            if($chapters[0]->getTopic() == $chapter)
            {
                $final = true;
            }
        }
        $chap = array();
        $pages_seen = $stud_chap->getProgress($course, $chapter, $this->getUser()->getProfile()->getId());
        if ($pages_seen == null) {
            $chap = $course_chapter->findChapter1($course, $chapter);
            if ($chap != null) {
                $student_chapter = new StudentChapter();
                $student_chapter->setStudent($this->getUser()->getProfile());
                $student_chapter->setChapter($chap);
                $student_chapter->setPagesCompleted(0);
                $student_chapter->setUpdatedAt(new DateTime());
                $em->persist($student_chapter);
                $em->flush();

                $pages_seen = array("pagesCompleted" => 0, 'id' => $student_chapter->getId());
            } else {
                return $this->redirectToRoute("student_course_index");
            }
        } else {
            $chap = $course_chapter->findChapter1($course, $chapter);
        }

        return $this->render('student_course/player.html.twig', [
            'contents' => $contents,
            'pages_seen' => $pages_seen,
            'chapter' => $chap,
            'final' => $final,
        ]);
    }

    /**
     * @Route("/{course}/cont", name="content_html", methods={"GET"})
     */
    public function htmlContent($course, ContentRepository $contentRepository, Request $request): Response
    {
        $content = $contentRepository->getHtmlContent($course);
        // dd($content);
        $response['content'] = $content["content"];
        $response['resources'] = $content["resource"];
        $response['resource_names'] = $content['resourceNames'];
    
        // Send all this stuff back to DataTables

        $returnResponse = new JsonResponse();
        $returnResponse->setJson(json_encode($response));
    
        return $returnResponse;
    }

    /**
     * @Route("/new/{id}", name="content_new", methods={"GET","POST"})
     */
    public function new(Request $request, InstructorCourse $instructorCourse, SluggerInterface $slugger, LogService $log, InstructorRepository $inst_repo): Response
    {
        
        $this->denyAccessUnlessGranted('content_create');
        $content = new Content();
        $em = $this->getDoctrine()->getManager();

        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $instructorCourse->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 

        $instChapter = $em->getRepository(InstructorCourseChapter::class)->findBy(array('instructorCourse'=> $instructorCourse->getId()));
        if(!$instChapter){
            $this->addFlash("danger", "Add chapter first");
            return $this->redirectToRoute('content_index', ['id' => $instructorCourse->getId()], Response::HTTP_SEE_OTHER);
        }
        
        $uploadSize = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'upload_size'])->getValue();
        if (!$uploadSize) {
            $uploadSize = 100;
        } //default.
        $form = $this->createForm(ContentType::class, $content, array('uploadSize' => $uploadSize, 'incrsid' => $instructorCourse->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $brochureFile = $form->get('filename')->getData();

            $youtubeLink = $form->get('videoLink')->getData();
            $youtubeLink = htmlspecialchars($youtubeLink);

            $resource = $form['resource']->getData();  

            if($youtubeLink)
            {
                $y = explode('=',$youtubeLink);
                if(sizeof($y) >= 2)
                {
                   // $youtubeLink  ='https://www.youtube.com/embed/'.explode('=',$youtubeLink)[1];
                    $last = explode('&',$y[1]);
                    if(sizeof($last) >= 2)
                    {
                        $youtubeLink  ='https://www.youtube.com/embed/'.$last[0];

                    }
                    else 
                    {
                        $youtubeLink  ='https://www.youtube.com/embed/'.$y[1];

                    }
                    $content->setVideoLink( $youtubeLink);
                }
            }

            if($resource){
                $originalFilename = pathinfo($resource->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resource->guessExtension();
                
                try {
                    $resource->move(
                        $this->getParameter('uploading_directory_resources'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $originalFilename = $resource->getClientOriginalName();
                $content->setResource($newFilename);
                $content->setResourceNames($originalFilename);
            }
          
            if ($brochureFile /*&& !$youtubeLink*/) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('uploading_directory_resources'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                $content->setFilename($newFilename);
            }

            $entityManager->persist($content);
            $entityManager->flush();
            
            $origional = $log->changeObjectToArray($content);

            $message = $log->snew($origional, "", "create", $this->getUser(), 'content');
            if(!$message)
            $this->addFlash("info", "Log not created");
            return $this->redirectToRoute('content_index', ['id' => $instructorCourse->getId()], Response::HTTP_SEE_OTHER);
        }
        // else{
        //     dd($form->getErrors());
        // }
        
        
        return $this->renderForm('content/new.html.twig', [
            'content' => $content,
            'form' => $form,
            'incrsid' => $instructorCourse->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        $this->denyAccessUnlessGranted('content_list');
        return $this->render('content/show.html.twig', [
            'content' => $content,
            'incrsid' => $content->getChapter()->getInstructorCourse()->getId(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Content $content, SluggerInterface $slugger, LogService $log, InstructorRepository $inst_repo): Response
    {
        $this->denyAccessUnlessGranted('content_edit');
        $instructor = $inst_repo->findOneBy(['user'=>$this->getUser()->getId()]);
        if ($instructor->getId() != $content->getChapter()->getInstructorCourse()->getInstructor()->getId()) {
            return $this->render('/bundles/TwigBundle/Exception/error404.html.twig');
        } 
        $em = $this->getDoctrine()->getManager();
        $origional = $log->changeObjectToArray($content);
        $uploadSize = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'upload_size'])->getValue();
        if (!$uploadSize) {
            $uploadSize = 100;
        }
        $form = $this->createForm(ContentType::class, $content, array('uploadSize' => $uploadSize, 'incrsid' => $content->getChapter()->getInstructorCourse()->getId()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fileName = $form['filename']->getData();
            $youtubeLink = $form->get('videoLink')->getData();
            $resource = $form['resource']->getData();  

            if($resource){
                $originalFilename = pathinfo($resource->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resource->guessExtension();
                
                try {
                    $resource->move(
                        $this->getParameter('uploading_directory_resources'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $originalFilename = $resource->getClientOriginalName();
                $content->setResource($newFilename);
                $content->setResourceNames($originalFilename);
            }

            /*https://youtu.be/kAj7FUGZKV8*/

            /*if($youtubeLink)
            {
                $y = explode('=',$youtubeLink);
                if(sizeof($y) == 2)
                {
                    $youtubeLink  ='https://www.youtube.com/embed/'.explode('=',$youtubeLink)[1];
                    $content->setVideoLink($youtubeLink);
                }
            }*/

            if($youtubeLink)
            {
                $y = explode('/',$youtubeLink);
                if(sizeof($y) == 4)
                {
                    $youtubeLink  ='https://www.youtube.com/embed/'.$y[3];
                    $content->setVideoLink($youtubeLink);
                }
            }



            if ($fileName) {
                $originalFilename = pathinfo($fileName->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$fileName->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fileName->move(
                        $this->getParameter('uploading_directory_videos'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $content->setFilename($newFilename);
            }

            $modified = $log->changeObjectToArray($content);
            $this->getDoctrine()->getManager()->flush();
            $message = $log->snew($origional, $modified, "update", $this->getUser(), 'content');
            // if(!$message)
                // $this->addFlash("info", "Log not created");
            return $this->redirectToRoute('content_index', ['id'=>$content->getChapter()->getInstructorCourse()->getId()], Response::HTTP_SEE_OTHER);
        }

        $resource = json_decode($content->getResourceNames());
        return $this->renderForm('content/edit.html.twig', [
            'content' => $content,
            'form' => $form,
            'incrsid' => $content->getChapter()->getInstructorCourse()->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="content_delete", methods={"POST"})
     */
    public function delete(Request $request, Content $content,LogService $log): Response
    {
        $this->denyAccessUnlessGranted('content_delete');
        $instid = $content->getChapter()->getInstructorCourse()->getId();

        if ($this->isCsrfTokenValid('delete' . $content->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            try {
                $entityManager->remove($content);
                $origional = $log->changeObjectToArray($content);
                $entityManager->flush();
                $message = $log->snew($origional, "", "delete", $this->getUser(), 'content');
            } catch (\Exception $ex) {
                
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('content_index', ['id' => $instid], Response::HTTP_SEE_OTHER);
    }
}
