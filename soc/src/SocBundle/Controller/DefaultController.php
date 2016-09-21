<?php

namespace SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      $categories = $em->getRepository('SocBundle:Category')->findAll();

      return $this->render('SocBundle:Default:index.html.twig', array(
        'categories' => $categories,
      ));
    }
}
