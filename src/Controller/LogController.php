<?php

namespace App\Controller;

use App\Entity\Log;
use App\Form\LogType;
use App\Repository\LogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/log")
 */
class LogController extends AbstractController
{
    /**
     * @Route("/", name="log_index", methods={"GET"})
     */
    public function index(LogRepository $logRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('see_log');
        $log = new Log();
        $searchForm = $this->createForm(LogType::class, $log);
        $searchForm->handleRequest($request);

        $queryBuilder=$logRepository->findLog($request->query->get('actor'),$request->query->get('createdAt'),$request->query->get('action'),$request->query->get('modifiedEntity'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            50
        );
        
        return $this->render('log/index.html.twig', [
            'logs' => $data,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    public function snew($original, $modified, $action, $actor){
        $em = $this->getDoctrine()->getManager();

        $log = new Log();
        $log->setAction($action);
        $log->setOriginal($original);
        $log->setModified($modified);
        $log->setCreatedAt(new DateTime());
        $log->setActor($actor);
        $em->persist($log);
        $em->flush();
        $message = $log->getId() ? true: false;
        return $message;
    }
    // /**
    //  * @Route("/new", name="log_new", methods={"GET", "POST"})
    //  */
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $log = new Log();
    //     $form = $this->createForm(LogType::class, $log);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($log);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('log_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('log/new.html.twig', [
    //         'log' => $log,
    //         'form' => $form,
    //     ]);
    // }

    /**
     * @Route("/{id}", name="log_show", methods={"GET"})
     */
    public function show(Log $log): Response
    {
        $this->denyAccessUnlessGranted('see_log');

        $original = json_decode($log->getOriginal(),true);
        $modified = json_decode($log->getModified(), true);
        return $this->render('log/show.html.twig', [
            'log' => $log,
            'original' => $original,
            'modified' => $modified
        ]);
    }

    /**
     * @Route("/{id}/edit", name="log_edit", methods={"GET", "POST"})
     */
    // public function edit(Request $request, Log $log, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(LogType::class, $log);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('log_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('log/edit.html.twig', [
    //         'log' => $log,
    //         'form' => $form,
    //     ]);
    // }

    /**
     * @Route("/{id}", name="log_delete", methods={"POST"})
     */
    // public function delete(Request $request, Log $log, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$log->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($log);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('log_index', [], Response::HTTP_SEE_OTHER);
    // }
}
