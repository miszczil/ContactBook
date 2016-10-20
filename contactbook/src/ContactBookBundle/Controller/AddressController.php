<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use ContactBookBundle\Entity\Address;


class AddressController extends Controller
{
    
    /**
     * @Route("/{id}/addAddress", name="addAddress")
     */
    public function addAddressAction(Request $request, $id) {
        
        $address = new Address();
        
        $form = $this->createFormBuilder($address)
            ->setMethod("POST")
            ->add("city", "text")
            ->add("street", "text")
            ->add("houseNo", "text")
            ->add("apartmentNo", "text", 
                    array("required" => false, 
                          "empty_data" => null))
            ->add("add", "submit")
            ->getForm(); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $owner = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Person")
                    ->find($id);
            
            $address->setOwner($owner);
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));

        }  
        
        return $this->render("ContactBookBundle:Address:addAddress.html.twig",
                        array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/editAddress/{addressId}", name="editAddress")
     */
    public function editAddressAction(Request $request, $id, $addressId) {
        
        $address = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Address")
                    ->find($addressId);
               
        $form = $this->createFormBuilder($address)
            ->setMethod("POST")
            ->add("city", "text")
            ->add("street", "text")
            ->add("houseNo", "text")
            ->add("apartmentNo", "text", 
                    array("required" => false, 
                          "empty_data" => null))
            ->add("edit", "submit")
            ->getForm();    
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));
            
        }
        
        return $this->render("ContactBookBundle:Address:editAddress.html.twig", 
                            array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/deleteAddress/{addressId}", name="deleteAddress")
     */
    public function deleteAddressAction($id, $addressId) {
        
        $em = $this->getDoctrine()->getManager();
        
        $address = $em->getRepository("ContactBookBundle:Address")->find($addressId);
        
        if ($address) {
            $em->remove($address);
            $em->flush();
        }
        
        return $this->redirectToRoute("show", array("id" => $id));
        
    }
    
    
}
