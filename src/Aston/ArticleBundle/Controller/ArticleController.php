<?php

namespace Aston\ArticleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aston\ArticleBundle\Entity\Article;
use Aston\ArticleBundle\Form\ArticleType;
use Aston\ArticleBundle\Form\ArticleModifType;

class ArticleController extends Controller
{
    public function indexAction()
    {
    	$articles = $this->getDoctrine()->getManager()->getRepository('AstonArticleBundle:Article')->findAll();
        return $this->render('AstonArticleBundle:Article:index.html.twig', array('articles' => $articles));
    }
    
    public function voirAction($id) {
    	$repository = $this->getDoctrine()->getManager()->getRepository('AstonArticleBundle:Article');
    	$article = $repository->find($id);
    	return $this->render('AstonArticleBundle:Article:voir.html.twig', array('article' => $article));
    }
    
    public function ajouterAction() {
    	$article = new Article();
    	$article->setDate(new \DateTime());
    	$form = $this->createForm(new ArticleType, $article);
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$bdd = $this->getDoctrine()->getManager();
    			$bdd->persist($article);
    			$bdd ->flush();
    			return $this->render('AstonArticleBundle:Article:voir.html.twig',
    				array('id' => $article->getId(), 'article' => $article));
    		}
    	}
    	
    	return $this->render('AstonArticleBundle:Article:ajouter.html.twig',
    		array('form' => $form->createView()));
    }
    
    public function modifierAction($id) {
    	$article = $this->getDoctrine()->getManager()->find('AstonArticleBundle:Article', $id);
    	$form = $this->createForm(new ArticleModifType, $article);
    	$request = $this->get('request');
    	
    	if($request->getMethod() == 'POST') {
    		$form->bind($request);
    		if ($form->isValid()) {
    			$bdd = $this->getDoctrine()->getManager();
    			$bdd->persist($article);
    			$bdd ->flush();
    			return $this->redirect($this->generateUrl('aston_article_voir', array('id' => $article->getId())));
    		}
    	}
    	
    	return $this->render('AstonArticleBundle:Article:modifier.html.twig', array('article' => $article, 'form' => $form->createView()));
    }
    
    public function supprimerAction($id) {
    	$bdd = $this->getDoctrine()->getManager();
    	$article = $bdd->find('AstonArticleBundle:Article', $id);
    	$bdd->remove($article);
    	$bdd->flush();
    	return $this->render('AstonArticleBundle:Article:supprimer.html.twig');
    }
}
