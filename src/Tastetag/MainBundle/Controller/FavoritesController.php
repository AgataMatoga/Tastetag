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

    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TastetagMainBundle:Favorites')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Favorite entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        if ($usr= $this->get('security.context')->getToken()->getUser()) {
            return $this->redirect($this->generateUrl('my_account'));
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }   
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
