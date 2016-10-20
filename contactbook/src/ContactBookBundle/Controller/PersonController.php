<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ContactBookBundle\Entity\Person;



class PersonController extends Controller
{
    /**
     * @Route("/new", name="new")
     */
    public function newAction(Request $request) {
        
        $person = new Person();
        
        $form = $this->createFormBuilder($person)
            ->setMethod("POST")
            ->add("firstName", "text")
            ->add("lastName", "text")
            ->add("description", "textarea")
            ->add("save", "submit")
            ->getForm(); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $person->setPhoto('default_photo.jpg');
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("showAll");

        }  
        
        return $this->render("ContactBookBundle:Person:new.html.twig",
                        array("form" => $form->createView()));
        
    }
    
    
    
    /**
     * @Route("/", name="showAll")
     */
    public function showAllAction(Request $request) {
        
        $contacts = $this->getDoctrine()
                            ->getRepository("ContactBookBundle:Person")
                            ->findAll();
        
        return $this->render("ContactBookBundle:Person:showAll.html.twig", 
                array("contacts" => $contacts));
    }
    
    /**
     * @Route("/{id}", name="show")
     */
    public function showAction(Request $request, $id) {
        
        $contact = $this->getDoctrine()
                            ->getRepository("ContactBookBundle:Person")
                            ->find($id);
        
        return $this->render("ContactBookBundle:Person:show.html.twig", 
                array("contact" => $contact));
    }
    
    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function editAction(Request $request, $id) {
        
        $person = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Person")
                    ->find($id);
        
        $form = $this->createFormBuilder($person)
            ->setMethod("POST")
            ->add("firstName", "text")
            ->add("lastName", "text")
            ->add("description", "textarea")
            ->add("save", "submit")
            ->getForm(); 

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));

        }  
        
        return $this->render("ContactBookBundle:Person:edit.html.twig", 
                array("form" => $form->createView()));
    }
    
    /**
     * @Route("{id}/changePhoto", name="changePhoto")
     */
    public function changePhotoAction($id) {
        
        $person = $this->getDoctrine()
                    ->getRepository("ContactBookBundle:Person")
                    ->find($id);
        
        $person->setPhoto(
            new File($this->getParameter('photos_directory').
                    '/'.$person->getPhoto())
                );
        
        $form = $this->createFormBuilder($person)
            ->setMethod("POST")
            ->add('photo', 'file', array('label' => 'Photo (JPG file)'))
            ->add("save", "submit")
            ->getForm(); 
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {

            $file = $person->getPhoto();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('photos_directory'),
                $fileName
            );

            $person->setPhoto($fileName);
            
            $task = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));

        }  
        
        return $this->render("ContactBookBundle:Person:changePhoto.html.twig", 
                array("form" => $form->createView()));
    }
    
    
    /**
     * @Route("/{id}/delete", name="delete")
     */
    public function deleteAction($id) {
        
        $em = $this->getDoctrine()->getManager();
        
        $person = $em->getRepository("ContactBookBundle:Person")->find($id);
        
        if ($person) {
            $em->remove($person);
            $em->flush();
        }
        
        return $this->redirectToRoute("showAll");
        
    }
    
    
    
    
}
