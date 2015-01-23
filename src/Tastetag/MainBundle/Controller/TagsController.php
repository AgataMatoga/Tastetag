<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TagsController extends Controller
{
    public function recipesAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $tag = $em->getRepository('TastetagMainBundle:Tags')->find($id);
        return $this->render('TastetagMainBundle:Tags:recipes.html.twig', array(
            'tag' => $tag
        ));
    }
}
