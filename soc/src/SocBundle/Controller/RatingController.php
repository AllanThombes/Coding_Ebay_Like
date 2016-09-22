<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocBundle\Entity\Rating;
use SocBundle\Form\RatingType;
use SocBundle\Entity\Product;
use SocBundle\Entity\User;


/**
 * Rating controller.
 *
 * @Route("/rating")
 */
class RatingController extends Controller
{

    /**
     * Creates a new Rating entity.
     *
     * @Route("/new", name="rating_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, User $user, Product $product, $type)
    {
        $rating = new Rating();
        $currentUser = $this->container->get('security.token_storage')->getToken()->getUser();
        if (!is_object($currentUser))
          return $this->redirectToRoute('fos_user_security_login');

        $form = $this->createForm('SocBundle\Form\RatingType', $rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // we check if this is a rating on user or product
            // and if it's not the current user or the current user's product
            if ($type == 'user' && $user->getId() != $currentUser->getId())
              $rating->setUserRated($user);
            elseif ($type == 'product' && $product->getUser() != $currentUser)
              $rating->setProductRated($product);
            else
              return $this->redirectToRoute('soc_homepage');
            $rating->setRater($currentUser);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rating);
            $em->flush();

            if ($type == 'user')
              return $this->redirectToRoute('user_show', array('id' => $user->getId()));
            else
              return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('rating/new.html.twig', array(
            'rating' => $rating,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a Rating entity.
     *
     * @Route("/{id}", name="rating_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Rating $rating)
    {
      $user = $this->getUser();

      if (!$user->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't delete ratings!");
      }
        $form = $this->createDeleteForm($rating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rating);
            $em->flush();
        }

        return $this->redirectToRoute('rating_index');
    }

    /**
     * Creates a form to delete a Rating entity.
     *
     * @param Rating $rating The Rating entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Rating $rating)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rating_delete', array('id' => $rating->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
