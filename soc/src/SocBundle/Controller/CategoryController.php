<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocBundle\Entity\Category;
use SocBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('SocBundle:Category')->findAll();

        return $this->render('category/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SocBundle:Category')->findAll();
        $category = new Category();

        if ($_POST) {
            $category->setTitle($_POST['category']['title']);
            if (intval ($_POST['category']['parentCateg']))
              $category->setParentCateg($em->getRepository('SocBundle:Category')->find(intval ($_POST['category']['parentCateg'][0])));
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }

        return $this->render('category/new.html.twig', array(
            'category' => $category,
            // 'form' => $form->createView(),
            'categories' => $categories
        ));
    }

    /**
    * Find products of a category and sub-categories
    */
    public function getAllProducts($category, $manager, $products) {
      //first we get the category's products
      $categProducts = $manager->getRepository('SocBundle:Product')->findByCategory($category);
      foreach ($categProducts as $prod) {
        array_push($products, $prod);
      }

      // then we get each child category's products
      $categChilds = $manager->getRepository('SocBundle:Category')->findByParentCateg($category);
      foreach ($categChilds as $key => $value) {
        $products = $this->getAllProducts($value, $manager, $products);
      }
      return $products;
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $em = $this->getDoctrine()->getManager();
        // $categProducts = $em->getRepository('SocBundle:Product')->findByCategory($category);
        $products = [];
        $products = $this->getAllProducts($category, $em, $products);

        return $this->render('category/show.html.twig', array(
            'category' => $category,
            'products' => $products,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        // $editForm = $this->createForm('SocBundle\Form\CategoryType', $category);
        // $editForm->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SocBundle:Category')->findAll();

        if ($_POST) {
            $category->setTitle($_POST['category']['title']);
            if (intval ($_POST['category']['parentCateg']))
              $category->setParentCateg($em->getRepository('SocBundle:Category')->find(intval ($_POST['category']['parentCateg'][0])));
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }

        return $this->render('category/edit.html.twig', array(
            'category' => $category,
            'categories' => $categories,
            // 'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", name="category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('category_index');
    }

    /**
     * Creates a form to delete a Category entity.
     *
     * @param Category $category The Category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
