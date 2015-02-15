<?php

namespace Tastetag\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
* @Route("/admin")
*/

class AdminController extends Controller
{

    /**
     * Admin panel homepage
     *
     * @Route("/admin", name="admin_panel")
    */
    public function indexAction()
    {
         return $this->render('TastetagMainBundle:Admin:index.html.twig');
    }

    /**
     * Listing all users
     *
     * @Route("/admin/users", name="admin_users")
    */
    public function manageUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('TastetagMainBundle:User')->findAll();

        return $this->render(
            'TastetagMainBundle:Admin:users.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Deactivating user account
     *
     * @Route("/admin/deactivate/{userId}", name="deactivate_user")
    */
    public function deactivateUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(0);

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_users'));
    }

    /**
     * Activating user account
     *
     * @Route("/admin/activate/{userId}", name="activate_user")
    */
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
