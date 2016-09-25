<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use SocBundle\Entity\Product;
use SocBundle\Form\ProductType;

/**
 * Product controller.
 *
 */
class ProductController extends Controller
{
    /**
     * Lists all Product entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('SocBundle:Product')->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
        ));
    }

    /**
     * Creates a new Product entity.
     *
     */
    public function newAction(Request $request)
    {
      $user = $this->getUser();
      if (!is_object($user)) {
          throw new AccessDeniedException("You must be logged in to create products.");
      }
        $product = new Product();
        $form = $this->createForm('SocBundle\Form\ProductType', $product);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SocBundle:Category')->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $product->setUser($user);
            $categ = $em->getRepository('SocBundle:Category')->findByTitle($_POST['parentCateg']);
            if ($categ)
              $product->setCategory($categ[0]);
            else
              return $this->render('product/new.html.twig', array('product' => $product,
                  'categories' => $categories,
                  'form' => $form->createView(),
                  'message' => 'Must select an existant category'
              ));
              $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Product entity.
     *
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     */
    public function editAction(Request $request, Product $product)
    {
      $currentUser = $product->getUser();
      $user = $this->getUser();
      if (!is_object($user) || !($user == $currentUser) && !$user->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't edit other's products!");
      }
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('SocBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SocBundle:Category')->findAll();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $product->setCategory($em->getRepository('SocBundle:Category')->find((int)$_POST['category']['parentCateg']));
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'categories' => $categories,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Product entity.
     *
     */
    public function deleteAction(Request $request, Product $product)
    {
      $user = $this->getUser();
      $currentUser = $product->getUser();
      if (!is_object($user) || !($user == $currentUser) && !$user->hasRole('ROLE_ADMIN') ) {
          throw new AccessDeniedException("You can't delete other's products!");
      }
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a Product entity.
     *
     * @param Product $product The Product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
