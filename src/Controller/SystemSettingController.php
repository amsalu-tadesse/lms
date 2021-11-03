<?php

namespace App\Controller;

use App\Entity\SystemSetting;
use App\Form\SystemSettingType;
use App\Repository\SystemSettingRepository;
use Knp\Component\Pager\PaginatorInterface;
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
     * @Route("/", name="system_setting_index", methods={"GET","POST"})
     */
    public function index(SystemSettingRepository $systemSettingRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if ($request->request->get('edit')) {
            $id = $request->request->get('edit');
            $system_setting = $systemSettingRepository->findOneBy(['id' => $id]);
            $form = $this->createForm(SystemSettingType::class, $system_setting);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('system_setting_index');
            }

            $queryBuilder = $systemSettingRepository->findSystemSetting($request->query->get('search'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('system_setting/index.html.twig', [
                'system_settings' => $data,
                'form' => $form->createView(),
                'edit' => $id,
            ]);

        }
        $system_setting = new SystemSetting();
        $form = $this->createForm(SystemSettingType::class, $system_setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($system_setting);
            $entityManager->flush();

            return $this->redirectToRoute('system_setting_index');
        }

        $queryBuilder = $systemSettingRepository->findSystemSetting($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('system_setting/index.html.twig', [
            'system_settings' => $data,
            'form' => $form->createView(),
            'edit' => false,
        ]);
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

    /**
     * @Route("/{id}", name="system_setting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SystemSetting $system_setting): Response
    {
        if ($this->isCsrfTokenValid('delete' . $system_setting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($system_setting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('system_setting_index');
    }
}
