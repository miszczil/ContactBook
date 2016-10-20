<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use ContactBookBundle\Entity\Phone;

class PhoneController extends Controller
{
    
    /**
     * @Route("/{id}/addPhone", name="addPhone")
     */
    public function addPhoneAction(Request $request, $id) {
        
        $phone = new Phone();
        
        $form = $this->createFormBuilder($phone)
            ->setMethod("POST")
            ->add("number", "text")
            ->add("type", "choice", [
                    "choices" => [
                        "Prywatny" => "private",
                        "Służbowy" => "business"
                    ],
                  "expanded" => false,
                  "multiple" => false,
                  "choices_as_values" => true
            ])
            ->add("add", "submit")
            ->getForm(); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $owner = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Person")
                    ->find($id);
            
            $phone->setOwner($owner);
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));

        }  
        
        return $this->render("ContactBookBundle:Phone:addPhone.html.twig",
                        array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/editPhone/{phoneId}", name="editPhone")
     */
    public function editPhoneAction(Request $request, $id, $phoneId) {
        
        $phone = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Phone")
                    ->find($phoneId);
               
        $form = $this->createFormBuilder($phone)
            ->setMethod("POST")
            ->add("number", "text")
            ->add("type", "choice", [
                    "choices" => [
                        "Prywatny" => "private",
                        "Służbowy" => "business"
                    ],
                  "expanded" => false,
                  "multiple" => false,
                  "choices_as_values" => true
            ])
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
        
        return $this->render("ContactBookBundle:Phone:editPhone.html.twig", 
                            array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/deletePhone/{phoneId}", name="deletePhone")
     */
    public function deletePhoneAction($id, $phoneId) {
        
        $em = $this->getDoctrine()->getManager();
        
        $phone = $em->getRepository("ContactBookBundle:Phone")->find($phoneId);
        
        if ($phone) {
            $em->remove($phone);
            $em->flush();
        }
        
        return $this->redirectToRoute("show.html.twig", array("id" => $id));
        
    }
    
}
