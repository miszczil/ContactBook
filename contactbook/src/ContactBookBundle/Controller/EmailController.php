<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use ContactBookBundle\Entity\Email;

class EmailController extends Controller
{
    
    /**
     * @Route("/{id}/addEmail", name="addEmail")
     */
    public function addEmailAction(Request $request, $id) {
        
        $email = new Email();
        
        $form = $this->createFormBuilder($email)
            ->setMethod("POST")
            ->add("address", "text")
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
            
            $email->setOwner($owner);
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));

        }  
        
        return $this->render("ContactBookBundle:Email:addEmail.html.twig",
                        array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/editEmail/{emailId}", name="editEmail")
     */
    public function editEmailAction(Request $request, $id, $emailId) {
        
        $email = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Email")
                    ->find($emailId);
               
        $form = $this->createFormBuilder($email)
            ->setMethod("POST")
            ->add("address", "text")
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
        
        return $this->render("ContactBookBundle:Email:editEmail.html.twig", 
                            array("form" => $form->createView()));
        
    }
    
    /**
     * @Route("/{id}/deleteEmail/{emailId}", name="deleteEmail")
     */
    public function deletePhoneAction($id, $emailId) {
        
        $em = $this->getDoctrine()->getManager();
        
        $email = $em->getRepository("ContactBookBundle:Email")->find($emailId);
        
        if ($email) {
            $em->remove($email);
            $em->flush();
        }
        
        return $this->redirectToRoute("show.html.twig", array("id" => $id));
        
    }
}
