<?php

namespace Tastetag\MainBundle\Controller;

use Tastetag\MainBundle\Entity\Recipes;
use Tastetag\MainBundle\Entity\RecipePhoto;
use Tastetag\MainBundle\Entity\Comments;
use Tastetag\MainBundle\Entity\Favorites;
use Tastetag\MainBundle\Entity\Ingridients;
use Tastetag\MainBundle\Entity\Tags;
use Tastetag\MainBundle\Entity\User;

use Tastetag\MainBundle\Form\RecipeType;
use Tastetag\MainBundle\Form\RecipePhotoType;
use Tastetag\MainBundle\Form\CommentType;
use Tastetag\MainBundle\Form\FavoriteType;
use Tastetag\MainBundle\Form\IngridientType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;



class RecipesController extends Controller
{

    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        if(!$recipe) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Taki przepis nie istnieje.'
            );
           return $this->redirect($this->generateUrl('homepage'));
        }
        $deleteForm = $this->createDeleteForm($id);
        $commentForm = $this->createCommentForm();
        $favoriteForm = $this->createFavoriteForm();

        $ingridients = $recipe->getIngridients();
        $usr= $this->get('security.context')->getToken()->getUser();

        return $this->render('TastetagMainBundle:Recipes:show.html.twig', array(
            'recipe' => $recipe,
            'delete_form' => $deleteForm->createView(),
            'comment_form' => $commentForm->createView(),
            'favorite_form' => $favoriteForm->createView(),
            'ingridients' => $ingridients,
            'user' => $usr,
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

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($recipe);
            $acl = $aclProvider->createAcl($objectIdentity);

            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

             if ($usr= $this->get('security.context')->getToken()->getUser()) {
                return $this->redirect($this->generateUrl('my_account'));
            } else {
                return $this->redirect($this->generateUrl('homepage'));
            }
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

        if(!$entity) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Taki przepis nie istnieje.'
            );
           return $this->redirect($this->generateUrl('homepage'));
        }

        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();

        if ((false === $securityContext->isGranted('EDIT', $entity)) and ($user->getAdmin() === false)) {
            return $this->redirect($this->generateUrl('homepage'));
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
            $images = $recipe->getImages();

            foreach($images as $image) {
                $image->upload();
                $em->persist($image);
            }

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

    private function createCommentForm()
    {
        return $this->createForm(new CommentType(), new Comments());
    }

    private function createFavoriteForm()
    {
        return $this->createForm(new FavoriteType(), new Favorites());
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
