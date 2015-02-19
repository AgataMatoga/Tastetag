<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;


class RecipesRepository extends EntityRepository
{
    /**
     * Saving recipe
     *
     * @param mixed $recipe recipe to save
     * @param mixed $usr recipe author
     * @return void
    */
    public function saveRecipe($recipe, $usr)
    {
        $recipe = $this->checkUniqueTags($recipe);
        $recipe->setUser($usr);
        $em = $this->getEntityManager();
        $em->persist($recipe);

        $images = $recipe->getImages();

        foreach ($images as $image) {
            $image->upload();
            $em->persist($image);
        }

        $em->flush();
    }

    /**
     * Updating recipe
     *
     * @param mixed $recipe updated recipe
     * @param array $ingridientsHistory recipe ingridients before update
     * @param array $imagesHistory recipe images before update
     * @return void
    */
    public function updateRecipe($recipe, $ingridientsHistory, $imagesHistory)
    {

        $em = $this->getEntityManager();

        foreach ($ingridientsHistory as $ingridient) {
            if (false === $recipe->getIngridients()->contains($ingridient)) {
                $em->remove($ingridient);
            }
        }
        foreach ($imagesHistory as $image) {
            if (false === $recipe->getImages()->contains($image)) {
                $em->remove($image);
                $image->removeUpload();
            }
        }

        $recipe = $this->checkUniqueTags($recipe);
        $images = $recipe->getImages();

        foreach ($images as $image) {
            $image->upload();
            $em->persist($image);
        }

        $em->persist($recipe);
        $em->flush();
    }

    /**
     * Removing recipe
     *
     * @param mixed $recipe deleted recipe
     * @return void
    */
    public function deleteRecipe($recipe)
    {
        $em = $this->getEntityManager();
        $em->remove($recipe);
        $em->flush();
    }

    /**
     * Getting recipes by keyword
     *
     * @param string $keyword keyword
     * @return void
    */
    public function findAllByKeyword($keyword)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE LOWER(r.name) LIKE LOWER(:keyword)')->setParameter('keyword', '%'.$keyword.'%')
            ->getResult();
    }

    /**
     * Getting recipes favorited by certain user
     *
     * @param integer $user_id user id
     * @return void
    */
    public function findAllFavoritedByUser($user_id)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE r.id IN (
                 SELECT f.recipeId FROM TastetagMainBundle:Favorites f WHERE f.userId = :user_id )')
            ->setParameter('user_id', $user_id)
            ->getResult();

    }

    /**
     * Getting all recipes according to params
     *
     * @param string $keyword keyword
     * @param integer $maxTime recipe max time of preparation
     * @param integer $maxDifficulty recipe max difficulty
     * @return void
    */
    public function findAllByFilters($keyword, $maxTime, $maxDifficulty)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE LOWER(r.name) LIKE LOWER(:keyword) 
                 AND r.difficulty <= :maxDifficulty AND (r.prepHour * 60 + r.prepMin) <= :maxTime')->setParameter('keyword', '%'.$keyword.'%')
            ->setParameter('maxDifficulty', $maxDifficulty)
            ->setParameter('maxTime', $maxTime)
            ->getResult();
    }

    /**
     * Getting all recipes that are tagged by given tags
     *
     * @param string $tags tags
     * @param integer $number number of given tags
     * @return void
    */
    public function findByTags($tags,$number)
    {
          $sql = " 
            SELECT r.id
            FROM recipes r
            INNER JOIN recipes_tags rt ON rt.recipe_id = r.id
            INNER JOIN tags t ON t.id = rt.tag_id
            WHERE t.name IN (".$tags.")
            GROUP BY r.id
            HAVING COUNT(DISTINCT t.name) = ".$number.";";


        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        $recipes = $stmt->fetchAll();

        if($recipes != null) {
            $recipeIds = array();
            foreach( $recipes as $recipe) {    
                array_push($recipeIds, $recipe['id']);
            }
            $recipeIdsStr = implode(',', $recipeIds);

            $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->select('r')
            ->from('TastetagMainBundle:Recipes', 'r')
            ->add('where', $qb->expr()->in('r.id', $recipeIdsStr));

            return $qb->getQuery()->getResult();
        } else {
            return $recipes;
        }
    }

    /**
     * Checking if recipe tags already exist in order to keep tags unique
     * @param mixed $recipe recipe
     * @return void
    */
    private function checkUniqueTags($recipe)
    {   
        $em = $this->getEntityManager();
        $availableTags = $em->getRepository('TastetagMainBundle:Tags')->findAll();
        $tagsCollection = array();
        foreach ($availableTags as $tag) {
               $tagsCollection[$tag->getId()] = $tag->getName();
        }

        foreach ($recipe->getTags() as $tag) {
            if (in_array($tag->getName(), $tagsCollection)) {
                $recipe->removeTag($tag);
                $availableTag = $em->getRepository('TastetagMainBundle:Tags')
                                   ->find(array_search($tag->getName(), $tagsCollection));
                $recipe->addTag($availableTag);
            }
        }
        return $recipe;
    }
}
