<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

use SocBundle\Entity\User;
use SocBundle\Form\UserType;

class UserController extends BaseController
{
    //get all users
    public function usersAction() {
        //access user manager services

        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();

        return $this->render('SocBundle:User:users.html.twig', array('users' =>   $users));
    }

    /**
     * Finds and displays a User entity and his products
     *
     */
    public function showAction(User $user)
    {
      $em = $this->getDoctrine()->getManager();
      $products = $em->getRepository('SocBundle:Product')->findByUser($user);

        $deleteForm = $this->createDeleteForm($user);

        return $this->render('SocBundle:User:show.html.twig', array(
            'user' => $user,
            'products' => $products,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
      $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
      if (!is_object($user) || !($user == $currentUser) && !$currentUser->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't edit other's!");
      }
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('SocBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('soc_users', array('id' => $user->getId()));
        }

        return $this->render('SocBundle:User:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
      $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
      if (!($user == $currentUser) && !$currentUser->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't delete someone else.");
      }
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('soc_users');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @param Product $product The User entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function adminAction(Request $request, User $user)
    {
      $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
      if (!$currentUser->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't do that!");
      }
      $user->setRoles(array('ROLE_ADMIN'));
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      return $this->redirectToRoute('soc_users');
    }
}

?>
