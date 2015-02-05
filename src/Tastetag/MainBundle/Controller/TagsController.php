<?php

namespace Tastetag\MainBundle\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
