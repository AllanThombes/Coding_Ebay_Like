<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocBundle\Entity\Price;
use SocBundle\Form\PriceType;

/**
 * Price controller.
 *
 * @Route("/price")
 */
class PriceController extends Controller
{
    /**
     * Lists all Price entities.
     *
     * @Route("/", name="price_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $prices = $em->getRepository('SocBundle:Price')->findAll();

        return $this->render('price/index.html.twig', array(
            'prices' => $prices,
        ));
    }

    /**
     * Creates a new Price entity.
     *
     * @Route("/new", name="price_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $price = new Price();
        $form = $this->createForm('SocBundle\Form\PriceType', $price);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            return $this->redirectToRoute('price_show', array('id' => $price->getId()));
        }

        return $this->render('price/new.html.twig', array(
            'price' => $price,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Price entity.
     *
     * @Route("/{id}", name="price_show")
     * @Method("GET")
     */
    public function showAction(Price $price)
    {
        $deleteForm = $this->createDeleteForm($price);

        return $this->render('price/show.html.twig', array(
            'price' => $price,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Price entity.
     *
     * @Route("/{id}/edit", name="price_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Price $price)
    {
        $deleteForm = $this->createDeleteForm($price);
        $editForm = $this->createForm('SocBundle\Form\PriceType', $price);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            return $this->redirectToRoute('price_edit', array('id' => $price->getId()));
        }

        return $this->render('price/edit.html.twig', array(
            'price' => $price,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
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
            $em->remove($price);
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
