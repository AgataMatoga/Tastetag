<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


class HomeController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('TastetagMainBundle:Recipes')->findAll();
        $tags = $em->getRepository('TastetagMainBundle:Tags')->findAllByRecipesCount();
        return $this->render('TastetagMainBundle:Home:index.html.twig', array(
            'entities' => $entities,
            'tags' => $tags
        ));
    }

   public function loginAction()
        {
            $request = $this->getRequest();
            $session = $request->getSession();

                if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
                        $error = $request->attributes->get(
                            SecurityContext::AUTHENTICATION_ERROR
                        );
                } else {
                        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
                        $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                }

            return $this->render('TastetagMainBundle:Home:login.html.twig',
                    array(
                        'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                        'error'         => $error,
                    )
            );
        }

    public function searchAction(Request $req)
        {
            $q = $req->request->all();
            $key = $q['keyword'];

            $em = $this->getDoctrine()->getEntityManager();
            $results = $em->getRepository('TastetagMainBundle:Recipes')->findAllByKeyword($key);
            return $this->render('TastetagMainBundle:Home:search.html.twig', array('results' => $results, 'req' => $q));
        }

      public function advancedSearchAction(Request $req)
        {
            $q = $req->request->all();
            $key = $q['keyword'];
            $maxTime = $q['max_time'];
            $maxDifficulty = $q['max_diff'];

            $em = $this->getDoctrine()->getEntityManager();
            $results = $em->getRepository('TastetagMainBundle:Recipes')->findAllByFilters($key, $maxTime, $maxDifficulty);
            return $this->render('TastetagMainBundle:Home:search.html.twig', array('results' => $results, 'req' => $q));
        }


}
