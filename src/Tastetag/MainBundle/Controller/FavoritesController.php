<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Favorites;
use Tastetag\MainBundle\Form\FavoriteType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FavoritesController extends Controller
{
    public function newAction($recipe_id)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $favorite = new Favorites();
        $favorite->setRecipe($recipe);
        $form = $this->createForm(new FavoriteType(), $favorite);

        return $this->render('TastetagMainBundle:Favorites:new.html.twig', array(
            'favorite' => $favorite,
            'form'   => $form->createView()
        ));
    }

    public function createAction($recipe_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $favorite  = new Favorites();
        $request = $this->getRequest();

        $form    = $this->createForm(new FavoriteType(), $favorite);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $favorite = $form->getData();
            $favorite->setRecipe($recipe);
            $usr= $this->get('security.context')->getToken()->getUser();
            $favorite->setUser($usr);
            $em->persist($favorite);
            $em->flush();
            return $this->redirect($this->generateUrl('recipe_show', array('id' => $recipe_id)));
        }

        return $this->redirect($this->generateUrl('recipe_show', array('id' => $recipe_id)));
    }

}
