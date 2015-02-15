<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Comments;
use Tastetag\MainBundle\Form\CommentType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommentsController extends Controller
{

    /**
     * New recipe comment
     *
     * @Route("/recipes/{recipe_id}/comment/new", name="recipe_comment_new")
    */
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


    /**
     * Create recipe comment
     *
     * @Route("/recipes/{recipe_id}/comment/create", name="recipe_comment_create")
    */
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
            $comment->setRecipe($recipe);
            $usr= $this->get('security.context')->getToken()->getUser();
            $comment->setUser($usr);

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
