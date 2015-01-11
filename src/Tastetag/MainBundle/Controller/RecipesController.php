<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Recipes;
use Tastetag\MainBundle\Entity\RecipePhoto;
use Tastetag\MainBundle\Form\RecipeType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class RecipesController extends Controller
{

    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TastetagMainBundle:Recipes')->find($id);

        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render('TastetagMainBundle:Recipes:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function newAction()
    {	
		$entity = new Recipes();
        $form   = $this->createForm(new RecipeType(), $entity);
        return $this->render('TastetagMainBundle:Recipes:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    public function createAction() 
    {
    	$entity  = new Recipes();
        $request = $this->getRequest();
        $form    = $this->createForm(new RecipeType(), $entity);

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();        

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('TastetagMainBundle:Recipes:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));

    }

    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }
        $editForm = $this->createForm(new RecipeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render('TastetagMainBundle:Recipes:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Recipe entity.');
        }
        $editForm   = $this->createForm(new RecipeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $editForm->bindRequest($request);
        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('recipe_show', array('id' => $id)));
        }
        return $this->render('TastetagMainBundle:Recipes:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Recipe entity.');
            }
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('homepage'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

}
