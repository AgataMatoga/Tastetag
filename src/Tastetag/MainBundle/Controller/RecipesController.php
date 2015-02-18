<?php

/**
 * Recipes controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RecipesController extends Controller
{
    /**
     * Show single recipe by id 
     *
     * @param  integer $id recipe id 
     * @return void
    */
    public function showAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        if (!$recipe) {
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
        $usr = $this->get('security.context')->getToken()->getUser();

        return $this->render(
            'TastetagMainBundle:Recipes:show.html.twig', array(
                'recipe' => $recipe,
                'delete_form' => $deleteForm->createView(),
                'comment_form' => $commentForm->createView(),
                'favorite_form' => $favoriteForm->createView(),
                'ingridients' => $ingridients,
                'user' => $usr,
            )
        );
    }
    
    /**
     * New recipe action
     * 
     * @return void
    */
    public function newAction()
    {	
        $entity = new Recipes();

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

        return $this->render(
            'TastetagMainBundle:Recipes:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()
            )
        );
    }

    /**
     * Create recipe action
     *
     * @return void
    */
    public function createAction() 
    {
        $recipe  = new Recipes();
        $request = $this->getRequest();
        $form    = $this->createForm(new RecipeType(), $recipe);
        $form->bind($request);
        
        if ($form->isValid()) {

            $usr = $this->get('security.context')->getToken()->getUser();
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('TastetagMainBundle:Recipes')
               ->saveRecipe($recipe, $usr);

            $aclProvider = $this->get('security.acl.provider');
            $objectIdentity = ObjectIdentity::fromDomainObject($recipe);
            $acl = $aclProvider->createAcl($objectIdentity);

            $securityContext = $this->get('security.context');
            $user = $securityContext->getToken()->getUser();
            $securityIdentity = UserSecurityIdentity::fromAccount($user);

            $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
            $aclProvider->updateAcl($acl);

            if ($usr = $this->get('security.context')->getToken()->getUser()) {
                return $this->redirect($this->generateUrl('my_account'));
            } else {
                return $this->redirect($this->generateUrl('homepage'));
            }
        }

        return $this->render(
            'TastetagMainBundle:Recipes:new.html.twig', array(
                'entity' => $recipe,
                'form'   => $form->createView()
            )
        );

    }

    /**
     * Edit recipe action
     *
     * @param  integer $id recipe id 
     * @return void
    */
    public function editAction($id)
    {   
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('TastetagMainBundle:Recipes')->find($id);

        if (!$entity) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Taki przepis nie istnieje.'
            );
            return $this->redirect($this->generateUrl('homepage'));
        }

        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $editPermissions = $securityContext->isGranted('EDIT', $entity);

        if ((false === $editPermissions) and (false === $user->getAdmin())) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        $editForm = $this->createForm(new RecipeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);
        return $this->render(
            'TastetagMainBundle:Recipes:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Update recipe action
     *
     * @param  integer $id recipe id 
     * @return void
    */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
        if (!$recipe) {
            throw $this->createNotFoundException('Unable to find Recipe recipe.');
        }

        $ingridientsHistory = new ArrayCollection();
        foreach ($recipe->getIngridients() as $ingridient) {
            $ingridientsHistory->add($ingridient);
        }

        $imagesHistory = new ArrayCollection();
        foreach ($recipe->getImages() as $image) {
            $imagesHistory->add($image);
        }

        $editForm   = $this->createForm(new RecipeType(), $recipe);
        $deleteForm = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('TastetagMainBundle:Recipes')
               ->updateRecipe($recipe, $ingridientsHistory, $imagesHistory);

            return $this->redirect(
                $this->generateUrl(
                    'recipe_show', array(
                        'id' => $id
                    )
                )
            );
        }
        return $this->render(
            'TastetagMainBundle:Recipes:edit.html.twig', array(
                'entity'      => $recipe,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Delete recipe action
     *
     * @param  integer $id recipe id 
     * @return void
    */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();
        $form->bindRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $recipe = $em->getRepository('TastetagMainBundle:Recipes')->find($id);
            if (!$recipe) {
                throw $this->createNotFoundException('Unable to find Recipe entity.');
            }
            $em->getRepository('TastetagMainBundle:Recipes')->deleteRecipe($recipe);
        }

        if ($usr = $this->get('security.context')->getToken()->getUser()) {
            return $this->redirect($this->generateUrl('my_account'));
        } else {
            return $this->redirect($this->generateUrl('homepage'));
        }   
    }

    /**
     * Create delete recipe form action
     *
     * @param  integer $id recipe id 
     * @return void
    */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * Delete comment recipe form action
     *
     * @return void
    */
    private function createCommentForm()
    {
        return $this->createForm(new CommentType(), new Comments());
    }

    /**
     * Delete favorite recipe form action
     *
     * @return void
    */
    private function createFavoriteForm()
    {
        return $this->createForm(new FavoriteType(), new Favorites());
    }

}
