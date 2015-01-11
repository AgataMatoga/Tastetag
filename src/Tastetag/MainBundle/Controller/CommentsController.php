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
        $recipe = $this->getRecipe($recipe_id);

        $comment = new Comment();
        $comment->setRecipe($recipe);
        $form   = $this->createForm(new CommentType(), $comment);

        return $this->render('TastetagMainBundle:Comments:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction($recipe_id)
    {
        $recipe = $this->getRecipe($recipe_id);

        $comment  = new Comment();
        $comment->setRecipe($recipe);
        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
            // TODO: Persist the comment entity

            return $this->redirect($this->generateUrl('TastetagMainBundle_homepage', array(
                'id' => $comment->getRecipe()->getId())) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('TastetagMainBundle:Comments:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

    protected function getRecipe($recipe_id)
    {
        $em = $this->getDoctrine()
                    ->getEntityManager();

        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        if (!$recipe) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $recipe;
    }
}
