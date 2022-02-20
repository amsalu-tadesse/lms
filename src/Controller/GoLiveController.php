<?php

namespace App\Controller;

use App\Entity\GoLive;
use App\Form\GoLiveType;
use App\Repository\GoLiveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \DateTime;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\MailerService;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

/**
 * @Route("/golive")
 */
class GoLiveController extends AbstractController
{
    /**
     * @Route("/", name="go_live_index", methods={"GET"})
     */
    public function index(GoLiveRepository $goLiveRepository): Response
    {
        return $this->render('go_live/index.html.twig', [
            'go_lives' => $goLiveRepository->findMyCourses($this->getUser()->getId()),
        ]);
    }

    /**
     * @Route("/new", name="go_live_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,MailerInterface $mailer, MailerService $mservice): Response
    {
        $goLive = new GoLive();
        $form = $this->createForm(GoLiveType::class, $goLive, ['uid'=>$this->getUser()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goLive->setCreatedAt(new DateTime())
;           $entityManager->persist($goLive);
            $entityManager->flush();

            $instructorCourse = $goLive->getInstructorCourse();
            $studentCourses = $instructorCourse->getStudentCourses();
            $liveDate = $form->getData()->getStartsAt();
            $instMsg = $form->getData()->getMessage();
            $date = strtotime($liveDate);
            $date = date('D/M/Y H:i:s', $date);

            // "M d, Y @ H:i:s"

            $deadline = $date;//date_format($date,"M d, Y @ H:i:s");

        $receivers = array();
        foreach ($studentCourses as $studentCourse) {
   
           $receivers[] = $studentCourse->getStudent()->getUser()->getEmail();
   
        }
        $course = $instructorCourse->getCourse();

        $subject  = "Live streaming appointment";
        $message = "Dear student, <br>  <br> The instructor for the course '".$course."' will be live streamed on ".$deadline. "<br><br> Message: <br>".$instMsg ;
        $emails = $receivers;
        $mservice->sendMultipleEmail($mailer, $message, $emails, $subject);

            return $this->redirectToRoute('go_live_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('go_live/new.html.twig', [
            'go_live' => $goLive,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="go_live_show", methods={"GET"})
     */
    public function show(GoLive $goLive): Response
    {
        return $this->render('go_live/show.html.twig', [
            'go_live' => $goLive,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="go_live_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, GoLive $goLive, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoLiveType::class, $goLive, ['uid'=>$this->getUser()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('go_live_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('go_live/edit.html.twig', [
            'go_live' => $goLive,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="go_live_delete", methods={"POST"})
     */
    public function delete(Request $request, GoLive $goLive, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goLive->getId(), $request->request->get('_token'))) {
            $entityManager->remove($goLive);
            $entityManager->flush();
        }

        return $this->redirectToRoute('go_live_index', [], Response::HTTP_SEE_OTHER);
    }
}
