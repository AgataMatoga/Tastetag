<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Comments;
use Tastetag\MainBundle\Form\CommentType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends Controller
{
    public function newAction($recipe_id)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $comment = new Comments();
        $comment->setRecipe($recipe);
        $comment->setRecipeId($recipe_id);
        $form   = $this->createForm(new CommentType(), $comment);

        return $this->render('TastetagMainBundle:Comments:new.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction($recipe_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $comment  = new Comments();
        $request = $this->getRequest();

        $form    = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $comment = $form->getData();
            $comment->setRecipeId($recipe->getId());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirect($this->generateUrl('recipe_show', array('id' => $recipe_id)));
        }

        return $this->render('TastetagMainBundle:Recipes:new.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

}
