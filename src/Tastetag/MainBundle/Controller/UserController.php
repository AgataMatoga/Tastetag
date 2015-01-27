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
}
