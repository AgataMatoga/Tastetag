<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('TastetagMainBundle:Recipes')->findAll();
        return $this->render('TastetagMainBundle:Home:index.html.twig', array(
            'entities' => $entities
        ));
    }
}
