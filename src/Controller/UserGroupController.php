<?php

namespace App\Controller;

use App\Entity\Permission;
use App\Entity\User;
use App\Entity\UserGroup;
use App\Form\UserGroupType;
use App\Form\Filter\UserGroupFilterType;
use App\Repository\UserGroupRepository;
use App\Repository\UserRepository;
use App\Repository\PermissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/usergroup")
 */
class UserGroupController extends AbstractController
{
    /**
     * @Route("/", name="user_group_index", methods={"GET","POST"})
     */
    public function index(UserGroupRepository $userGroupRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $userGroup = new UserGroup();
        $searchForm = $this->createForm(UserGroupFilterType::class, $userGroup);
        $searchForm->handleRequest($request);

        // $this->denyAccessUnlessGranted('vw_usr_grp');
        if ($request->request->get('edit')) {
            // $this->denyAccessUnlessGranted('edt_usr_grp');

            $id=$request->request->get('edit');
            $userGroup=$userGroupRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(UserGroupType::class, $userGroup);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // $this->denyAccessUnlessGranted('edt_usr_grp');
                $userGroup->setUpdatedAt(new \DateTime());
                $userGroup->setUpdatedBy($this->getUser());
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('user_group_index');
            }
            $queryBuilder=$userGroupRepository->findUserGroup($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                18
            );
            return $this->render('user_group/index.html.twig', [
                    'user_groups' => $data,
                    'form' => $form->createView(),
                    'searchForm' => $searchForm->createView(),
                    'edit'=>$id
                ]);
        }

        $form = $this->createForm(UserGroupType::class, $userGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $this->denyAccessUnlessGranted('ad_usr_grp');

            $entityManager = $this->getDoctrine()->getManager();
            $userGroup->setIsActive(true);
            $userGroup->setCreatedAt(new \DateTime());
            $userGroup->setRegisteredBy($this->getUser());
            $entityManager->persist($userGroup);
            $entityManager->flush();

            return $this->redirectToRoute('user_group_index');
        }
        $queryBuilder=$userGroupRepository->findUserGroup($request->query->get('name'), $request->query->get('description'), $request->query->get('isActive'));
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            18
        );
        return $this->render('user_group/index.html.twig', [
                'user_groups' => $data,
                'form' => $form->createView(),
                'searchForm' => $searchForm->createView(),
                'edit'=>false
            ]);
    }
    /**
     * @Route("/{id}/users", name="user_group_users", methods={"POST","get"})
     */
    public function user(UserGroup $userGroup, Request $request, UserRepository $userRepository, PermissionRepository $permissionRepository)
    {
        //$this->denyAccessUnlessGranted('ad_usr_t_grp');

        if ($request->request->get('save')) {
            $users=$userRepository->findAll();
            foreach ($users as $user) {
                $userGroup->removeUser($user);
            }
            $users=$userRepository->findBy(['id'=>$request->request->get('user')]);
            foreach ($users as $user) {
                $userGroup->addUser($user);
            }
            $userGroup->setUpdatedAt(new \DateTime());
            $userGroup->setUpdatedBy($this->getUser());
            $this->getDoctrine()->getManager()->flush();
        }

        // $em = $this->getDoctrine()->getManager();
        $assignedUsers = $userGroup->getUsers()->toArray();
        $assignedPermissions = $userGroup->getPermission()->toArray();

        $assignedUsers =  $userGroup->getUsers()->toArray();
        $assignedPermissions =$userGroup->getPermission()->toArray();
        // dd($assignedPermissions);
        $assignedUsersId = array();
        $assignedPermId = array();

        foreach ($assignedUsers as $user) {
            $assignedUsersId[] = $user->getId();
        }

        foreach ($assignedPermissions as $perm) {
            $assignedPermId[] = $perm->getId();
        }
        // dd($permissionRepository->findAll());
        return $this->render(
            'user_group/user.html.twig',
            [
                'user_group' => $userGroup,
                'permission' => $permissionRepository->findAll(),
                'users' => $userRepository->findAll(),
                'users_exist' => $assignedUsersId,
                'perm_exist' => $assignedPermId
            ]
        );
    }
    /**
     * @Route("/{id}/permission", name="user_group_permission", methods={"POST"})
     */
    public function permission(UserGroup $userGroup, Request $request, PermissionRepository $permissionRepository)
    {
        // $this->denyAccessUnlessGranted('ad_prmsn_t_grp');

        if ($request->request->get('usergrouppermission')) {
            $permissions=$permissionRepository->findAll();
            foreach ($permissions as $permission) {
                $userGroup->removePermission($permission);
            }
            $permissions=$permissionRepository->findBy(['id'=>$request->request->get('permission')]);
            foreach ($permissions as $permission) {
                $userGroup->addPermission($permission);
            }
            $userGroup->setUpdatedAt(new \DateTime());
            $userGroup->setUpdatedBy($this->getUser());
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('user_group/permission.html.twig', [
            'user_group' => $userGroup,
            'permissions' => $permissionRepository->findForUserGroup($userGroup->getPermission()),

        ]);
    }
    /**
     * @Route("/{id}/activate", name="user_group_action", methods={"POST"})
     */
    public function action(UserGroup $userGroup, Request $request)
    {
        // $this->denyAccessUnlessGranted('edt_usr_grp');

        $userGroup->setIsActive($request->request->get('activateUserGroup'));
        $userGroup->setUpdatedAt(new \DateTime());
        $userGroup->setUpdatedBy($this->getUser());
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('user_group_index');
    }

    /**
     * @Route("/{id}", name="user_group_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserGroup $userGroup): Response
    {
        //$this->denyAccessUnlessGranted('dlt_usr_grp');
        if ($userGroup->getUsers()) {
            $this->addFlash("warning", "This user group cannot be deleted!!");
            return $this->redirectToRoute('user_group_index');
        }
        if ($this->isCsrfTokenValid('delete'.$userGroup->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();


            try {
                $entityManager->remove($userGroup);
                $entityManager->flush();
            } catch (\Exception $ex) {
                // dd($ex);
                $message = UtilityController::getMessage($ex->getCode());
                $this->addFlash('danger', $message);
            }
        }

        return $this->redirectToRoute('user_group_index');
    }

    /**
    * @Route("/savegroup/{id}", name="user_group_permission1", methods={"POST","GET"})
    */
    public function SaveUserGroupPermission(Request $request, UserGroup $userGroup)
    {
        $em = $this->getDoctrine()->getManager();
        $newPermList = $request->request->get("permission");
        $NewUserList = $request->request->get("user");

        //delete all permissions under this group
        $oldPermissionLists = $userGroup->getPermission();

        foreach ($oldPermissionLists as $key => $oldperm) {
            $userGroup->removePermission($oldperm);
        }

        //delete all user lists under this group
        $oldUserLists = $userGroup->getUsers();

        foreach ($oldUserLists as $key => $olduser) {
            $userGroup->removeUser($olduser);
        }

        if ($newPermList) {
            foreach ($newPermList as $key => $newperm) {
                $newPermisson = $em->getRepository(Permission::class)->find($newperm);
                $userGroup->addPermission($newPermisson);
            }
        }

        if ($NewUserList) {
            foreach ($NewUserList as $key => $newuser) {
                $newuser = $em->getRepository(User::class)->find($newuser);
                $userGroup->addUser($newuser);
            }
        }

        $em->flush();


        return $this->redirectToRoute("user_group_users", ['id'=>$userGroup->getId()]);
    }
}
