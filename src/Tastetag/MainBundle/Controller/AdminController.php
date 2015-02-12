<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{

    public function indexAction()
    {
         return $this->render('TastetagMainBundle:Admin:index.html.twig');
    }

	public function manageUsersAction()
    {

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('TastetagMainBundle:User')->findAll();

        return $this->render('TastetagMainBundle:Admin:users.html.twig', array(
            'users' => $users,
        ));
    }

    public function deactivateUserAction($userId)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(0);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_users'));
    }

    public function activateUserAction($userId)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(1);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_users'));
    }

}
