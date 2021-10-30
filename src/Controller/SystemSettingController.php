<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Form\SystemSetting1Type;
use App\Repository\SystemSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/system/setting")
 */
class SystemSettingController extends AbstractController
{
    /**
     * @Route("/", name="system_setting_index", methods={"GET"})
     */
    public function index(SystemSettingRepository $systemSettingRepository): Response
    {
        return $this->render('system_setting/index.html.twig', [
            'system_settings' => $systemSettingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="system_setting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $systemSetting = new SystemSetting();
        $form = $this->createForm(SystemSetting1Type::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($systemSetting);
            $entityManager->flush();

            return $this->redirectToRoute('system_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('system_setting/new.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="system_setting_show", methods={"GET"})
     */
    public function show(SystemSetting $systemSetting): Response
    {
        return $this->render('system_setting/show.html.twig', [
            'system_setting' => $systemSetting,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="system_setting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SystemSetting $systemSetting): Response
    {
        $form = $this->createForm(SystemSetting1Type::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_setting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('system_setting/edit.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="system_setting_delete", methods={"POST"})
     */
    public function delete(Request $request, SystemSetting $systemSetting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$systemSetting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($systemSetting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('system_setting_index', [], Response::HTTP_SEE_OTHER);
    }
}
