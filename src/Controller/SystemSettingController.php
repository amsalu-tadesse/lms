<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Form\SystemSettingType;
use App\Repository\SystemSettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/systemsetting")
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
     * @Route("/activesemester", name="activesemester", methods={"GET"})
     */
    public function readActiveSemeter(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $em->getRepository(SystemSetting::class)->findBy(['code' => 'active_semester'])[0];
        return $this->render('system_setting/active_semester.html.twig', [
            'systemsettings' => $setting,
        ]);
    }

    /**
     * @Route("/editactivesemester", name="editactivesemester", methods={"POST"})
     */
    public function editactivesemester(Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $sem = $request->request->get("semester");
        $setting = $em->getRepository(SystemSetting::class)->findBy(['code' => 'active_semester'])[0];
        $setting->setValue($sem);
        $em->flush();
        return $this->redirectToRoute("activesemester");
    }

    /**
     * @Route("/new", name="system_setting_new", methods={"GET","POST"})
     */
    function new (Request $request): Response {
        $systemSetting = new SystemSetting();
        $form = $this->createForm(SystemSettingType::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($systemSetting);
            $entityManager->flush();

            return $this->redirectToRoute('system_setting_index');
        }

        return $this->render('system_setting/new.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="system_setting_show", methods={"GET"})
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
        $form = $this->createForm(SystemSettingType::class, $systemSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getValue() < 0) {
                $this->addFlash('danger', 'Please enter a positive number');
                return $this->redirectToRoute('system_setting_index');
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('system_setting_index');
        }

        return $this->render('system_setting/edit.html.twig', [
            'system_setting' => $systemSetting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="system_setting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SystemSetting $systemSetting): Response
    {
        if ($this->isCsrfTokenValid('delete' . $systemSetting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($systemSetting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('system_setting_index');
    }
    /**
     * @Route("/temsandconditions", name="temsandconditions", methods={"GET"})
     */
    public function termsandconditions(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $tems = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'terms'])->getValue();
        $conditions = $em->getRepository(SystemSetting::class)->findOneBy(['code' => 'conditions'])->getValue();
        return $this->render('system_setting/termsandconditions.html.twig', [
            'terms' => $tems,
            'conditions' => $conditions,
        ]);
    }
}
