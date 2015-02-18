<?php

/**
 * Comments controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

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
     * @param  integer $recipe_id recipe id
     * @return array   $comment   comment
    */
    public function newAction($recipe_id)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $comment = new Comments();
        $comment->setRecipe($recipe);

        $form   = $this->createForm(new CommentType(), $comment);

        return $this->render(
            'TastetagMainBundle:Comments:new.html.twig', array(
                 'comment' => $comment,
                 'form'   => $form->createView()
             )
        );
    }


    /**
     * Create recipe comment
     *
     * @param  integer $recipe_id recipe id
     * @return array   $comment   comment
    */
    public function createAction($recipe_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);
        $comment = new Comments();
        $request = $this->getRequest();
        $form = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $comment = $form->getData();
            $usr = $this->get('security.context')->getToken()->getUser();
            $em->getRepository('TastetagMainBundle:Comments')
               ->saveComment($usr, $recipe, $comment);

            return $this->redirect(
                $this->generateUrl(
                    'recipe_show', array(
                        'id' => $recipe_id
                    )
                )
            );
        }

        return $this->render(
            'TastetagMainBundle:Recipes:new.html.twig', array(
                'comment' => $comment,
                'form'    => $form->createView()
            )
        );
    }

}
