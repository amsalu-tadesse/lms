<?php

namespace App\Controller;

use App\Entity\Content;
use App\Form\ContentType;
use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/content")
 */
class ContentController extends AbstractController
{
   
     /**
     * @Route("/lessons", name="studentview", methods={"GET"})
     */
    public function studentview(ContentRepository $contentRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder=$contentRepository->findContent($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('content/studentview.html.twig', [
             'contents' => $data,
            // 'form' => $form->createView(),
            'edit'=>false
        ]);
    }
   
     /**
     * @Route("/studentlesson/page/{id}", name="studentlesson", methods={"GET","POST"})
     */
    public function studentviewtwo(ContentRepository $contentRepository,Request $request, PaginatorInterface $paginator): Response
    {
        //$nextId = $request->get('id');
        //$data=$contentRepository->find($nextId);

        $queryBuilder=$contentRepository->findContent($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            1
        );

        return $this->render('content/studentviewtwo.html.twig', [
             'contents' => $data,
            'edit'=>false
        ]);
    }
   
     /**
     * @Route("/studentlesson/prev/{id}", name="prevpage", methods={"GET","POST"})
     */
    // public function prevPage(ContentRepository $contentRepository,Request $request, PaginatorInterface $paginator): Response
    // {
    //     $prevId = $request->get('id');
    //     $data=$contentRepository->find($prevId);
    //     while (!$data ||  $prevId < 1) {
    //         $data=$contentRepository->find($prevId -1);
    //     }
    //     return $this->render('content/studentviewtwo.html.twig', [
    //          'contents' => $data,
    //         'edit'=>false
    //     ]);
    // }
   
     /**
     * @Route("/studentlesson/next/{id}", name="nextpage", methods={"GET","POST"})
     */
    // public function nextPage(ContentRepository $contentRepository,Request $request, PaginatorInterface $paginator): Response
    // {
    //     $nextId = $request->get('id');
    //     $data=$contentRepository->find($nextId);
    //     while (!$data) {
    //         $data=$contentRepository->find($nextId + 1);
    //     }

    //     return $this->render('content/studentviewtwo.html.twig', [
    //          'contents' => $data,
    //         'edit'=>false
    //     ]);
    // }

    /**
     * @Route("/", name="content_index", methods={"GET"})
     */
    public function index(ContentRepository $contentRepository,Request $request, PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $content=$contentRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ContentType::class, $content);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('content_index');
            }

            $queryBuilder=$contentRepository->findContent($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                5
            );
            return $this->render('content/index.html.twig', [
                'contents' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('content_index');
        }
        
        $queryBuilder=$contentRepository->findContent($request->query->get('search'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('content/index.html.twig', [
            'contents' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }  








    /**
     * @Route("/new/{id}", name="content_new", methods={"GET","POST"})
     */
    public function new(Request $request,ContentType $contentType, SluggerInterface $slugger): Response
    {
 
        $content = new Content();
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();


            $brochureFile = $form->get('filename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('uploading_directory_resources'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $content->setFilename($newFilename);
            }


            $entityManager->persist($content);
            $entityManager->flush();

            return $this->redirectToRoute('content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('content/new.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="content_show", methods={"GET"})
     */
    public function show(Content $content): Response
    {
        return $this->render('content/show.html.twig', [
            'content' => $content,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="content_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Content $content): Response
    {
        $form = $this->createForm(ContentType::class, $content);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('content_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('content/edit.html.twig', [
            'content' => $content,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="content_delete", methods={"POST"})
     */
    public function delete(Request $request, Content $content): Response
    {
        if ($this->isCsrfTokenValid('delete'.$content->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($content);
            $entityManager->flush();
        }

        return $this->redirectToRoute('content_index', [], Response::HTTP_SEE_OTHER);
    }
}
