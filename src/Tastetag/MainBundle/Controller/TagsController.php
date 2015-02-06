<?php

namespace Tastetag\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class TagsController extends Controller
{
    public function recipesAction($id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
        $tag = $em->getRepository('TastetagMainBundle:Tags')->find($id);
        $recipes = $tag->getRecipes();
        return $this->render('TastetagMainBundle:Tags:recipes.html.twig', array(
            'tag' => $tag,
            'recipes' => $recipes
        ));
    }

    public function showAction(Request $request)
    {   
        $tagname =  $this->getRequest()->request->get('tag_live');
         $tags = $this->getDoctrine()
                     ->getRepository('TastetagMainBundle:Tags')
                     ->findAllByName($tagname);
        return $this->render('TastetagMainBundle:Tags:show.html.twig', array(
            'tags' => $tags,
        ));
    }


    public function liveSearchAction(Request $request)
    {

        $string = $this->getRequest()->request->get('tagname');

        $tags = $this->getDoctrine()
                     ->getRepository('TastetagMainBundle:Tags')
                     ->findAllByName($string);

        $tags_array = array();
        foreach($tags as $tag) {
            array_push($tags_array, $tag->getName());
        }

        $response = array("code" => 100, "success" => true, "tags" => $tags_array);
            
        return new Response(json_encode($response)); 
    }

    public function tagSearchAction(Request $req)
    {		
    	$q = $req->request->all();

        $tags= array();
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
        $recipes = $em->getRepository('TastetagMainBundle:Recipes')->findByTags($tagsStr, $number);
        return $this->render('TastetagMainBundle:Tags:search.html.twig', array(
            'recipes' => $recipes,
            'req' => $q,
            'tags' => $tagsStr
        ));
    }
}
