<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tastetag\MainBundle\Form\RegistrationType;
use Tastetag\MainBundle\Form\Model\Registration;
use Tastetag\MainBundle\Entity\User;

class UserController extends Controller
{
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

    public function createAction()
	{
	    $em = $this->getDoctrine()->getEntityManager();

	    $form = $this->createForm(new RegistrationType(), new Registration());

	    $form->bind($this->getRequest());

	    if ($form->isValid()) {
	        $registration = $form->getData();
            $user = $registration->getUser();

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(),$user->getSalt());
            $user->setPassword($password);

            $em->persist($user);
	        $em->flush();

	        return $this->redirect($this->generateUrl('homepage'));
	    }

	    return $this->render(
	        'TastetagMainBundle:User:register.html.twig',
	        array('form' => $form->createView())
	    );
	}

    public function profileAction($user_id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usr = $em->getRepository('TastetagMainBundle:User')->find($user_id);
        $recipes =  $usr->getRecipes();

        return $this->render('TastetagMainBundle:User:profile.html.twig', array(
            'user' => $usr,
            'recipes' => $recipes
        ));
    }

    public function accountAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $usr = $this->get('security.context')->getToken()->getUser();
        $recipes =  $usr->getRecipes();
        $fav_recipes =  $em->getRepository('TastetagMainBundle:Recipes')->findAllFavoritedByUser($usr->getId());

        foreach ($recipes as $recipe) {
            $deleteForms[$recipe->getId()] = $this->createDeleteRecipeForm($recipe->getId())->createView();
        }

        return $this->render('TastetagMainBundle:User:account.html.twig', array(
            'user' => $usr,
            'recipes' => $recipes,
            'fav_recipes' => $fav_recipes,
            'deleteForms' => $deleteForms
        ));
    }


    private function createDeleteRecipeForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
