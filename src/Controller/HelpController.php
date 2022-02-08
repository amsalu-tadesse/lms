<?php

namespace App\Controller;

use App\Entity\Help;
use App\Entity\SystemSetting;
use App\Form\HelpType;
use App\Repository\HelpRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\LogService;
/**
 * @Route("/help")
 */
class HelpController extends AbstractController
{
    /**
     * @Route("/", name="help_index", methods={"GET","POST"})
     */
    public function index(HelpRepository $helpRepository, SluggerInterface $slugger, Request $request, PaginatorInterface $paginator): Response
    {

        $em = $this->getDoctrine()->getManager();
        $uploadSize = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'upload_size'])->getValue();
        if (!$uploadSize) {
            $uploadSize = 100;//default value is 100M ;
        } //

        if ($request->request->get('edit')) {
            $id=$request->request->get('edit');
            $help=$helpRepository->findOneBy(['id'=>$id]);
          
            $form = $this->createForm(HelpType::class, $help, ['uploadSize'=>$uploadSize]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                
 

                $resource = $form['attachment']->getData();
                $originalFilename = pathinfo($resource->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resource->guessExtension();
                
                try {
                    $resource->move(
                        $this->getParameter('uploading_directory_helps'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    die("error on uplaoding the file");
                }
                $originalFilename = $resource->getClientOriginalName();
                $help->setAttachment($newFilename);
               // $help->setResourceNames($originalFilename);
               $em->persist($help);
               $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('help_index');
            }

            $queryBuilder=$helpRepository->findHelp($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('help/index.html.twig', [
                'helps' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);
        }
        $help = new Help();
        $form = $this->createForm(HelpType::class, $help, ['uploadSize'=>$uploadSize]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();



                $resource = $form['attachment']->getData();
                $originalFilename = pathinfo($resource->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$resource->guessExtension();
                
                try {
                    $resource->move(
                        $this->getParameter('uploading_directory_helps'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    die("error on uplaoding the file");
                }
                $originalFilename = $resource->getClientOriginalName();
                $help->setAttachment($newFilename);
               // $help->setResourceNames($originalFilename);
  



            $entityManager->persist($help);
            $entityManager->flush();

            return $this->redirectToRoute('help_index');
        }

        $queryBuilder=$helpRepository->findHelp($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('help/index.html.twig', [
            'helps' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/{id}", name="help_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Help $help): Response
    {
        if ($this->isCsrfTokenValid('delete'.$help->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($help);
            $entityManager->flush();
        }

        return $this->redirectToRoute('help_index');
    }
}
