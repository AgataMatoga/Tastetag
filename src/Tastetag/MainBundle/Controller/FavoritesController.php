<?php

/**
 * Favorites controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Favorites;
use Tastetag\MainBundle\Form\FavoriteType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FavoritesController extends Controller
{
    
    /**
     * New favorite recipe action
     *
     * @param  integer $recipe_id favorite recipe id
     * @return array   favorite   new favorite 
    */
    public function newAction($recipe_id)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);

        $favorite = new Favorites();
        $favorite->setRecipe($recipe);
        $form = $this->createForm(new FavoriteType(), $favorite);

        return $this->render(
            'TastetagMainBundle:Favorites:new.html.twig', array(
                'favorite' => $favorite,
                'form'   => $form->createView()
            )
        );
    }

    /**
     * Create favorite recipe action
     *
     * @param  integer $recipe_id favorite recipe id
     * @return integer $recipe_id favorite recipe id 
    */
    public function createAction($recipe_id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($recipe_id);
        $favorite = new Favorites();
        $request = $this->getRequest();
        $form = $this->createForm(new FavoriteType(), $favorite);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $favorite = $form->getData();
            $usr = $this->get('security.context')->getToken()->getUser();

            $em->getRepository('TastetagMainBundle:Favorites')
               ->saveFavorite($recipe, $usr, $favorite);

            return $this->redirect(
                $this->generateUrl(
                    'recipe_show', array(
                        'id' => $recipe_id
                    )
                )
            );
        }

        return $this->redirect(
            $this->generateUrl(
                'recipe_show', array(
                    'id' => $recipe_id
                )
            )
        );
    }


    /**
     * Delete favorite recipe action
     *
     * @param  integer $id favorite id
     * @return void
    */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('TastetagMainBundle:Favorites')
               ->removeFavorite($id);
        }

        if ($usr = $this->get('security.context')->getToken()->getUser()) {
            return $this->redirect($this->generateUrl('my_account'));
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }   
    }


    /**
     * Create delete favorite recipe function
     *
     * @param  integer $id favorite id
     * @return void
    */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

}
