<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TastetagMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
