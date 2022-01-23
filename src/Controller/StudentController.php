<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\User;
use App\Form\StudentType;
use App\Form\ProfileType;
use App\Form\ProfileType2;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Services\LogService;

/**
 * @Route("/studentf")
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/", name="student_index", methods={"GET"})
     */
    public function index(StudentRepository $studentRepository): Response
    {
        return $this->render('student/index.html.twig', [
            'students' => $studentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/profile", name="student_profile", methods={"GET","POST"})
     */
    public function profile(StudentRepository $studentRepository, Request $request, SluggerInterface $slugger, LogService $log): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
       
        $origional = $log->changeObjectToArray($user);
        
        if($user->getProfile()->getProfileUpdated())
            $form = $this->createForm(ProfileType2::class, $user);
        else
            $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        $updated = $user->getProfile()->getProfileUpdated();

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($user);
            $em->flush();

            $modified = $log->changeObjectToArray($user);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "user");

            $image = $form['profilePicture']->getData();
            $student = $em->getRepository(Student::class)->findOneBy(['user'=>$this->getUser()->getId()]);
            
            $origional = $log->changeObjectToArray($student);

            $student->setProfileUpdated(1);
            if($image)
            {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('uploading_directory_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $student->setProfilePicture($newFilename);   
            }
            $em->persist($student);
            $em->flush();
            $modified = $log->changeObjectToArray($student);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "student");

            return $this->redirectToRoute("student_profile");
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'profile_updated' => $updated,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="student_new", methods={"GET","POST"})
     */
    public function new(Request $request, LogService $log): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($student);
            $entityManager->flush();

            $origional = $log->changeObjectToArray($student);
            $message = $log->snew($origional, "", "create", $this->getUser(), "student");

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_show", methods={"GET"})
     */
    public function show(Student $student): Response
    {
        return $this->render('student/show.html.twig', [
            'student' => $student,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="student_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Student $student, LogService $log): Response
    {
        $origional = $log->changeObjectToArray($student);
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $modified = $log->changeObjectToArray($student);
            $message = $log->snew($origional, $modified, "update", $this->getUser(), "student");

            return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="student_delete", methods={"POST"})
     */
    public function delete(Request $request, Student $student, LogService $log): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $origional = $log->changeObjectToArray($student);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($student);
            $entityManager->flush();
            $message = $log->snew($origional, "", "delete", $this->getUser(), "student");
        }

        return $this->redirectToRoute('student_index', [], Response::HTTP_SEE_OTHER);
    }
}
