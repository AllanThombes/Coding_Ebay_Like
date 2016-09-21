<?php

namespace SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class JsonController extends Controller
{
    public function findCategoriesLike($term, $limit = 100)
    {
      $repository = $this->getDoctrine()
          ->getRepository('SocBundle:Category');

      $query = $repository->createQueryBuilder('c')
      ->where('c.title LIKE :term')
      ->setParameter('term', '%'.$term.'%')
      ->setMaxResults($limit)
      ->getQuery();

      $arrayAss= $query->getArrayResult();
      // $arrayAss = $repository->findAll();

      // Transformer le tableau associatif en un tableau standard
      $array = array();
      foreach($arrayAss as $data)
      {
      $array[] = array("category"=>$data['title']);
      }

      return $array;
    }
    public function categoriesAction()
    {
      // $request = $this->get('request_stack');

      // $term = $request->getCurrentRequest();
      $term = $_POST['data'];

      $array= $this->findCategoriesLike($term);
      $response = new Response(json_encode($array));
      $response -> headers -> set('Content-Type', 'application/json');
      return $response;

    }
}
?>
