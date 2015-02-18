<?php

/**
 * Admin controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

namespace Tastetag\MainBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{

    /**
     * Admin panel homepage
     *
     * @return void
    */
    public function indexAction()
    {
         return $this->render('TastetagMainBundle:Admin:index.html.twig');
    }

    /**
     * Listing all users
     *
     * @return array $users users
    */
    public function manageUsersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('TastetagMainBundle:User')->findAll();

        return $this->render(
            'TastetagMainBundle:Admin:users.html.twig', array(
                'users' => $users,
             )
        );
    }

    /**
     * Deactivating user account
     *
     * @param  integer $userId User id
     * @return void
    */
    public function deactivateUserAction($userId)
    {   
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('TastetagMainBundle:User')->deactivateUser($userId);
        return $this->redirect($this->generateUrl('admin_users'));
    }

    /**
     * Activating user account
     *
     * @param  integer $userId User id
     * @return void
    */
    public function activateUserAction($userId)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('TastetagMainBundle:User')->activateUser($userId);

        return $this->redirect($this->generateUrl('admin_users'));
    }

}
