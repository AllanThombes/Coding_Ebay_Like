<?php

namespace SocBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SocBundle\Entity\Bid;
use SocBundle\Form\BidType;
use SocBundle\Entity\Price;
use SocBundle\Entity\Product;
use Symfony\Component\Validator\Constraints\Date;


/**
 * Bid controller.
 *
 * @Route("/bid")
 */
class BidController extends Controller
{
    /**
     * Lists all Bid entities.
     *
     * @Route("/", name="bid_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bids = $em->getRepository('SocBundle:Bid')->findAll();

        return $this->render('bid/index.html.twig', array(
            'bids' => $bids,
        ));
    }

    /**
     * Creates a direct buy
     *
     * @Route("/new", name="product_buy")
     * @Method({"GET", "POST"})
     */
    public function buyAction(Request $request, Product $product)
    {
        //must be log in to buy
        if (!$this->getUser())
          return $this->redirectToRoute('fos_user_security_login');
        $bid = new Bid();
        $price = $product->getBid();
        //if it's not a direct buy we get back to product's List
        if ($price->getStatus() != "direct buy")
          return $this->redirectToRoute('product_index');
          $em = $this->getDoctrine()->getManager();
          $date = new \DateTime();
          //everything is ok, bid is registered
          $bid->setPrice($price);
          $bid->setUser($this->getUser());
          $bid->setAmount($price->getImmediatePrice());
          $bid->setAutomatic(false);
          $price->setStatus('Sold');
          $em->persist($price);
          $em->persist($bid);
          $em->flush();
            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
    }

    /**
     * Creates a new Bid entity.
     *
     * @Route("/new", name="bid_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Product $product)
    {
        //must be log in to bid
        if (!$this->getUser())
          return $this->redirectToRoute('fos_user_security_login');
        $bid = new Bid();
        $price = $product->getBid();
        //if it's not a bidding we get back to product's List
        if ($price->getStatus() != "bidding")
          return $this->redirectToRoute('product_index');
        $form = $this->createForm('SocBundle\Form\BidType', $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          //bid must be at less equal to last price + min bid
          $minprice = ($price->getactualPrice() + $price->getMinBid());
          if ($bid->getAmount() < $minprice) {
              $message = "Your bid is not high enough, must be at least: ".$minprice;
              return $this->render('bid/new.html.twig', array(
                  'bid' => $bid,
                  'form' => $form->createView(),
                  'message'=> $message
              ));
          }
          $em = $this->getDoctrine()->getManager();
          $date = new \DateTime();
          //if price date is over bid is cancelled
          if ($date > $price->getEndDate() ) {
            //if there are bids the product is sold
            if ($price->getBids())
              $price->setStatus('Sold');
            else
              //no bids the sold is closed
              $price->setStatus('Closed');
            $em->persist($price);
            $em->flush();
            $message = "Too late, bid are closed";
            return $this->redirectToRoute('product_show', array('id' => $product->getId(), 'message' => $message));
          }
          //everything is ok, bid is registered
            $bid->setPrice($price);
            $bid->setUser($this->getUser());
            $price->setActualPrice($bid->getAmount());
            //if immediate price is reach, product is Sold
            if (($price->getImmediatePrice() > 0) && $price->getActualPrice() >= $price->getImmediatePrice())
              $price->setStatus('Sold');
            $em->persist($price);
            $em->persist($bid);
            $em->flush();

          //we call the function that chek if there is automatic bid
          $this->checkAutomatic($bid, $price);

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('bid/new.html.twig', array(
            'bid' => $bid,
            'form' => $form->createView(),
        ));
    }

    //Check if there is other automatic bid and place a bid if so
    //then it call itself until ther is no more to place
    public function checkAutomatic(Bid $lastBid, Price $price) {
        $em = $this->getDoctrine()->getManager();
        $autoBidTemp = $em->getRepository('SocBundle:Bid')->findBy(array('price' => $price,'automatic' => true), array( 'bidDate' => 'DESC'));
        //if the first result is the one we just place we check next one
        if ($autoBidTemp[0] == $lastBid)
          $autoBid = $autoBidTemp[1];
        else
          $autoBid = $autoBidTemp[0];
        //if we found an autobid (which is not current one) and if its max is greater than actual price + min bid
        //we place a bid by increasing with min bid
        if ($autoBid && $autoBid->getMaxBid() >= ($price->getActualPrice() + $price->getMinBid()) && $price->getStatus() != 'Sold') {
          $newAutoBid = new Bid();
          $newAutoBid->setAmount($price->getActualPrice() + $price->getMinBid());
          $newAutoBid->setAutomatic(true);
          $newAutoBid->setMaxBid($autoBid->getMaxBid());
          $newAutoBid->setPrice($price);
          $newAutoBid->setUser($autoBid->getUser());
          //Check date before finalize transaction
          $date = new \DateTime();
          if ($date > $price->getEndDate() ) {
            if ($price->getBid()) {
              $price->setStatus('Sold');
              $em->persist($price);
              $em->flush();
              return $this->redirectToRoute('product_show', array('id' => $price->getProduct()->getId()));
            }
          }
          $price->setActualPrice($newAutoBid->getAmount());
          //if immediate price is reach, product is Sold
          if (($price->getImmediatePrice() > 0) && ($price->getActualPrice() >= $price->getImmediatePrice())) {
            $price->setStatus('Sold');
          }
          $em->persist($newAutoBid);
          $em->persist($price);
          $em->flush();
          //if the last bid placed before is automatic we must chain bid placement
          if ($lastBid->getAutomatic())
            $this->checkAutomatic($newAutoBid, $price);
        }

    }

    /**
     * Finds and displays a Bid entity.
     *
     * @Route("/{id}", name="bid_show")
     * @Method("GET")
     */
    public function showAction(Bid $bid)
    {
        $deleteForm = $this->createDeleteForm($bid);

        return $this->render('bid/show.html.twig', array(
            'bid' => $bid,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Bid entity.
     *
     * @Route("/{id}/edit", name="bid_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Bid $bid)
    {
        $deleteForm = $this->createDeleteForm($bid);
        $editForm = $this->createForm('SocBundle\Form\BidType', $bid);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bid);
            $em->flush();

            return $this->redirectToRoute('bid_edit', array('id' => $bid->getId()));
        }

        return $this->render('bid/edit.html.twig', array(
            'bid' => $bid,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Bid entity.
     *
     * @Route("/{id}", name="bid_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Bid $bid)
    {
        $form = $this->createDeleteForm($bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bid);
            $em->flush();
        }

        return $this->redirectToRoute('bid_index');
    }

    /**
     * Creates a form to delete a Bid entity.
     *
     * @param Bid $bid The Bid entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bid $bid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bid_delete', array('id' => $bid->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
