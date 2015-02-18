<?php

/**
 * Tags controller.
 *
 * @category Tastetag
 * @package  Tastetag
 * @author   Agata Matoga <agatka.ma@gmail.com>
 * @license  http://some.com Some
 * @link     http://wierzba.wzks.uj.edu.pl/~10_matoga/tastetag
 */

namespace Tastetag\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TagsController extends Controller
{   
    /**
     * Show all recipes tagged by tag
     *
     * @param  integer $id tag id
     * @return void
    */
    public function recipesAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $tag = $em->getRepository('TastetagMainBundle:Tags')->find($id);
        if (!$tag) {
            $this->get('session')->getFlashBag()->add(
                'notice',
                'Taki tag nie istnieje.'
            );
            return $this->redirect($this->generateUrl('homepage'));
        }
        $recipes = $tag->getRecipes();
        return $this->render(
            'TastetagMainBundle:Tags:recipes.html.twig', array(
                'tag' => $tag,
                'recipes' => $recipes
            )
        );
    }

    /**
     * Function returns tags by tag name
     *
     * @param  mixed[] $request array with params from post request
     * @return void
    */
    public function showAction(Request $request)
    {   
        $tagname =  $this->getRequest()->request->get('tag_live');
        $tags = $this->getDoctrine()
                     ->getRepository('TastetagMainBundle:Tags')
                     ->findAllByName($tagname);

        return $this->render(
            'TastetagMainBundle:Tags:show.html.twig', array(
                'tags' => $tags
            )
        );
    }

    /**
     * Ajax function returns tags by tag keyword
     *
     * @param  mixed[] $request array with params from post request
     * @return void
    */
    public function liveSearchAction(Request $request)
    {

        $string = $this->getRequest()->request->get('tagname');

        $tags = $this->getDoctrine()
                     ->getRepository('TastetagMainBundle:Tags')
                     ->findAllByName($string);

        $tags_array = array();
        foreach ($tags as $tag) {
            array_push($tags_array, $tag->getName());
        }

        $response = array("code" => 100, "success" => true, "tags" => $tags_array);
            
        return new Response(json_encode($response)); 
    }

    /**
     * Function returns all recipes that are tagged with given tags
     *
     * @param  mixed[] $req array with params from post request
     * @return void
    */
    public function tagSearchAction(Request $req)
    {
        $q = $req->request->all();
        
        $tags = array();
        if ($q['tag1'] != '') {
            array_push($tags, "'".$q['tag1']."'");
        }
        if ($q['tag2'] != '') {
            array_push($tags, "'".$q['tag2']."'");
        }
        if ($q['tag3'] != '') {
            array_push($tags, "'".$q['tag3']."'");
        }
        $number = count($tags);
        $tagsStr = implode(",", $tags);
        $em = $this->getDoctrine()->getEntityManager();
        $recipes = $em->getRepository('TastetagMainBundle:Recipes')
                      ->findByTags($tagsStr, $number);
        return $this->render(
            'TastetagMainBundle:Tags:search.html.twig', array(
                'recipes' => $recipes,
                'req' => $q,
                'tags' => $tagsStr
            )
        );
    }
}
