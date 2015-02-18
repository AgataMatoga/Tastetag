<?php

/**
 * User controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tastetag\MainBundle\Form\RegistrationType;
use Tastetag\MainBundle\Form\Model\Registration;
use Tastetag\MainBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * Register action
     *
     * @return void
    */
    public function registerAction()
    {
        $form = $this->createForm(
            new RegistrationType(),
            new Registration()
        );

        return $this->render(
            'TastetagMainBundle:User:register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Create user action
     *
     * @return void
    */
    public function createAction()
    {
        $form = $this->createForm(new RegistrationType(), new Registration());
        $form->bind($this->getRequest());
        
        if ($form->isValid()) {
            $registration = $form->getData();
            $user = $registration->getUser();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword(
                $user->getPassword(), $user->getSalt()
            );

            $em = $this->getDoctrine()->getEntityManager();
            $em->getRepository('TastetagMainBundle:User')
               ->saveUser($user, $password);

            return $this->redirect($this->generateUrl('homepage'));
        }

         return $this->render(
             'TastetagMainBundle:User:register.html.twig', array(
                 'form' => $form->createView()
             )
         );
    }

    /**
     * Create user action
     *
     * @param  integer $user_id User id
     * @return void
    */
    public function profileAction($user_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usr = $em->getRepository('TastetagMainBundle:User')->find($user_id);
        if (!$usr) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Taki użytkownik nie istnieje.'
            );
            return $this->redirect($this->generateUrl('homepage'));
        }

        $recipes =  $usr->getRecipes();

        return $this->render(
            'TastetagMainBundle:User:profile.html.twig', array(
                'user' => $usr,
                'recipes' => $recipes
            )
        );
    }

    /**
     * User private account 
     *
     * @return void
    */
    public function accountAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $usr = $this->get('security.context')->getToken()->getUser();
        $recipes =  $usr->getRecipes();
        $fav_recipes =  $em->getRepository('TastetagMainBundle:Recipes')
                           ->findAllFavoritedByUser($usr->getId());

        $favs = $em->getRepository('TastetagMainBundle:Favorites')
                   ->findByUserId($usr->getId());

        $deleteForms = array();
        $deleteFavForms = array();

        foreach ($recipes as $recipe) {
            $deleteForms[$recipe->getId()] = $this->createDeleteRecipeForm($recipe->getId())
                                                  ->createView();
        }

        foreach ($favs as $fav) {
            $deleteFavForms[$fav->getId()] = $this->createDeleteFavoriteForm($fav->getId())
                                                  ->createView();
        }

        return $this->render(
            'TastetagMainBundle:User:account.html.twig', array(
                'user' => $usr,
                'recipes' => $recipes,
                'favs' => $favs,
                'deleteForms' => $deleteForms,
                'deleteFavForms' => $deleteFavForms
            )
        );
    }

    /**
     * Create delete recipe form action
     * 
     * @param  integer $id recipe id
     * @return void
    */
    private function createDeleteRecipeForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }

    /**
     * Create delete favorite form
     *
     * @param  integer $id recipe id
     * @return void
    */
    private function createDeleteFavoriteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
