<?php

namespace Aston\LivreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aston\LivreBundle\Entity\Livre;
use Aston\LivreBundle\Form\LivreType;
use Aston\LivreBundle\Form\LivreModifType;

class LivreController extends Controller
{
    public function indexAction()
    {
    	$livres = $this->getDoctrine()->getManager()->getRepository('AstonLivreBundle:Livre')->findAll();
        return $this->render('AstonLivreBundle:Livre:index.html.twig', array('livres' => $livres));
    }
    
    public function voirAction($id) {
    	$repository = $this->getDoctrine()->getManager()->getRepository('AstonLivreBundle:Livre');
    	$livre = $repository->find($id);
    	return $this->render('AstonLivreBundle:Livre:voir.html.twig', array('livre' => $livre));
    }
    
    public function ajouterAction() {
    	$livre = new Livre();
    	$livre->setDate(new \DateTime());
    	$form = $this->createForm(new LivreType, $livre);
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$bdd = $this->getDoctrine()->getManager();
    			$bdd->persist($livre);
    			$bdd ->flush();
    			return $this->render('AstonLivreBundle:Livre:voir.html.twig',
    				array('id' => $livre->getId(), 'livre' => $livre));
    		}
    	}
    	
    	return $this->render('AstonLivreBundle:Livre:ajouter.html.twig',
    		array('form' => $form->createView()));
    }
    
    public function modifierAction($id) {
    	$livre = $this->getDoctrine()->getManager()->find('AstonLivreBundle:Livre', $id);
    	$form = $this->createForm(new LivreModifType, $livre);
    	$request = $this->get('request');
    	
    	if($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$bdd = $this->getDoctrine()->getManager();
    			$bdd->persist($livre);
    			$bdd ->flush();
    			return $this->redirect($this->generateUrl('aston_livre_voir', array('id' => $livre->getId())));
    		}
    	}
    	
    	return $this->render('AstonLivreBundle:Livre:modifier.html.twig', array('livre' => $livre, 'form' => $form->createView()));
    }
    
    public function supprimerAction($id) {
    	$bdd = $this->getDoctrine()->getManager();
    	$livre = $bdd->find('AstonLivreBundle:Livre', $id);
    	$bdd->remove($livre);
    	$bdd->flush();
    	return $this->render('AstonLivreBundle:Livre:supprimer.html.twig');
    }
}
