<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocBundle\Entity\Price;
use SocBundle\Form\PriceType;
use SocBundle\Entity\Product;

/**
* Price controller.
*
* @Route("/price")
*/
class PriceController extends Controller
{
  
  /**
  * Creates a new Price entity.
  *
  * @Route("/{id}/new", name="price_new")
  * @Method({"GET", "POST"})
  */
  public function newAction(Request $request, Product $product)
  {
    $user = $product->getUser();
    $currentUser = $this->getUser();
    if (!is_object($user) || !($user == $currentUser) ) {
      throw new AccessDeniedException("You can't sell other's products!");
    }
    if ($product->getBid())
      throw new AccessDeniedException("You can't put to sell a product 2 times.");

    $price = new Price();
    $form = $this->createForm('SocBundle\Form\PriceType', $price);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      if ($price->getStartingPrice())
        $price->setActualPrice($price->getStartingPrice());
      else
        $price->setActualPrice($price->getImmediatePrice());
      $price->setProduct($product);
      $product->setBid($price);
      $em = $this->getDoctrine()->getManager();
      $em->persist($price);
      $em->persist($product);
      $em->flush();

      return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    return $this->render('price/new.html.twig', array(
      'price' => $price,
      'form' => $form->createView(),
      'product' => $product
    ));
  }

  /**
  * Deletes a Price entity.
  *
  * @Route("/{id}", name="price_delete")
  * @Method("DELETE")
  */
  public function deleteAction(Request $request, Price $price)
  {
    $form = $this->createDeleteForm($price);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $price->setStatus('Cancelled');
      $em->persist($price);
      $em->flush();
    }

    return $this->redirectToRoute('price_index');
  }

  /**
  * Creates a form to delete a Price entity.
  *
  * @param Price $price The Price entity
  *
  * @return \Symfony\Component\Form\Form The form
  */
  private function createDeleteForm(Price $price)
  {
    return $this->createFormBuilder()
    ->setAction($this->generateUrl('price_delete', array('id' => $price->getId())))
    ->setMethod('DELETE')
    ->getForm()
    ;
  }
}
