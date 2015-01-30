<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Recipes;
use Tastetag\MainBundle\Entity\RecipePhoto;
use Tastetag\MainBundle\Entity\Comments;
use Tastetag\MainBundle\Entity\Ingridients;
use Tastetag\MainBundle\Entity\Tags;

use Tastetag\MainBundle\Form\RecipeType;
use Tastetag\MainBundle\Form\RecipePhotoType;
use Tastetag\MainBundle\Form\CommentType;
use Tastetag\MainBundle\Form\IngridientType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;


class RecipesController extends Controller
{

    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        $deleteForm = $this->createDeleteForm($id);
        $commentForm = $this->createCommentForm();

        $ingridients = $recipe->getIngridients();

        return $this->render('TastetagMainBundle:Recipes:show.html.twig', array(
            'recipe' => $recipe,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $commentForm->createView(),
            'ingridients' => $ingridients,
        ));
    }

    public function newAction()
    {	
        $entity = new Recipes();

        //dummy code
        $ingridient1 = new Ingridients();
        $ingridient1->setName('skladnik1');
        $entity->getIngridients()->add($ingridient1);

        $image1 = new RecipePhoto();
        $image1->setName('skladnik1');
        $entity->getImages()->add($image1);

        $tag1 = new Tags();
        $tag1->setName('tag1');
        $entity->getTags()->add($tag1);
		
        $form   = $this->createForm(new RecipeType(), $entity);
        return $this->render('TastetagMainBundle:Recipes:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    public function createAction() 
    {
    	$recipe  = new Recipes();
        $request = $this->getRequest();
        $form    = $this->createForm(new RecipeType(), $recipe);
        $form->bind($request);
        
        if ($form->isValid()) {

            $recipe = $this->checkUniqueTags($recipe);

            $usr= $this->get('security.context')->getToken()->getUser();
            $recipe->setUser($usr);

            $em = $this->getDoctrine()->getManager();
            $em->persist($recipe);
            $em->flush();

            $images = $recipe->getImages();

            foreach($images as $image) {
                $image->upload();
                $em->persist($image);
            }
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('TastetagMainBundle:Recipes:new.html.twig', array(
            'entity' => $recipe,
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

        $originalIngridients = new ArrayCollection();
        foreach ($entity->getIngridients() as $ingridient) {
            $originalIngridients->add($ingridient);
        }

        $originalImages = new ArrayCollection();
        foreach ($entity->getImages() as $image) {
            $originalImages->add($image);
        }

        $editForm   = $this->createForm(new RecipeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $editForm->bindRequest($request);
        if ($editForm->isValid()) {
            foreach ($originalIngridients as $ingridient) {
                if (false === $entity->getIngridients()->contains($ingridient)) {
                    $em->remove($ingridient);
                }
            }
            foreach ($originalImages as $image) {
                if (false === $entity->getImages()->contains($image)) {
                    $em->remove($image);
                    $image->removeUpload();
                }
            }

            $recipe = $this->checkUniqueTags($entity);

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

    private function createCommentForm()
    {
        return $this->createForm(new CommentType(), new Comments());
    }

    private function checkUniqueTags($recipe)
    {   
        $em = $this->getDoctrine()->getManager();
        $availableTags = $em->getRepository('TastetagMainBundle:Tags')->findAll();
        $tagsCollection = array();
        foreach ($availableTags as $tag) {
               $tagsCollection[$tag->getId()] = $tag->getName();
        }

        foreach ($recipe->getTags() as $tag) {
            if (in_array($tag->getName(), $tagsCollection)) {
                 $recipe->removeTag($tag);
                 $availableTag = $em->getRepository('TastetagMainBundle:Tags')->find(array_search($tag->getName(), $tagsCollection));
                 $recipe->addTag($availableTag);
            }
        }
        return $recipe;
    }

}
