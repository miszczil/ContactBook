<?php

namespace ContactBookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use ContactBookBundle\Entity\Label;

class LabelController extends Controller {

    /**
     * @Route("/newLabel", name="newLabel")
     */
    public function newLabelAction(Request $request) {

        $label = new Label();

        $form = $this->createFormBuilder($label)
                ->setMethod("POST")
                ->add("name", "text")
                ->add("save", "submit")
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("showAll");
        }

        return $this->render("ContactBookBundle:Label:newLabel.html.twig", array("form" => $form->createView()));
    }

    /**
     * @Route("/{id}/addLabels", name="addLabels")
     */
    public function addLabelsAction(Request $request, $id) {

        $person = $this->getDoctrine()
                ->getRepository("ContactBookBundle:Person")
                ->find($id);

        $form = $this->createFormBuilder($person)
            ->setMethod("POST")
            ->add("labels", "entity", array(
                "class" => 'ContactBookBundle:Label',
                "choice_label" => "name",
                "multiple" => true,
                "expanded" => true,
            ))
            ->add("add", "submit")
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute("show", array("id" => $id));
        }

        return $this->render("ContactBookBundle:Label:addLabels.html.twig", 
                array("form" => $form->createView()));
    }

    
    /**
     * @Route("/label/{labelId}", name="showByLabel")
     */
    public function showByLabelAction($labelId) {
        
        $qb = $this->getDoctrine()
                ->getRepository('ContactBookBundle:Person')
                ->createQueryBuilder('p');
                
        $result = $qb->innerJoin('p.labels', 'l')
                    ->where('l.id = :label_id')
                    ->setParameter('label_id', $labelId)
                    ->getQuery()->getResult();

        
        return $this->render("ContactBookBundle:Person:showAll.html.twig", 
                array("contacts" => $result));
    }
}
