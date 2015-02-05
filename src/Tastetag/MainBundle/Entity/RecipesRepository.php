<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RecipesRepository extends EntityRepository
{
    public function findAllByKeyword($keyword)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE LOWER(r.name) LIKE LOWER(:keyword)')->setParameter('keyword', '%'.$keyword.'%')
            ->getResult();
    }

    public function findAllByFilters($keyword, $maxTime, $maxDifficulty)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE LOWER(r.name) LIKE LOWER(:keyword) 
                 AND r.difficulty <= :maxDifficulty')->setParameter('keyword', '%'.$keyword.'%')->setParameter('maxDifficulty', $maxDifficulty)
            ->getResult();
    }

    public function findByTags($tags, $number)
    {
          $sql = " 
           SELECT r.*
            FROM   recipes r
             INNER JOIN (SELECT rt.recipe_id
                         FROM recipes_tags rt
                                  INNER JOIN recipes r
                                    ON r.id = rt.recipe_id
                                  INNER JOIN tags t
                                    ON t.id = rt.tag_id
                         WHERE    t.name IN (".$tags.")
                         GROUP BY rt.recipe_id
                         HAVING   Count(rt.recipe_id) =".$number.") aa
               ON r.id = aa.recipe_id
        ";

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->execute();

        $recipes = $stmt->fetchAll();

        $recipeIds = array();
        foreach( $recipes as $recipe) {    
            array_push( $recipeIds, $recipe['id']);
        }
        $tagIdsStr = implode(',', $recipeIds);
        // return $stmt->fetchAll();

        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE r.id IN (:ids)')->setParameter('ids', $tagIdsStr)
            ->getResult();
    }
}
