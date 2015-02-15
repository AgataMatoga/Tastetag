<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;


class RecipesRepository extends EntityRepository
{
    public function findAllByKeyword($keyword)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE LOWER(r.name) LIKE LOWER(:keyword)')->setParameter('keyword', '%'.$keyword.'%')
            ->getResult();
    }

     public function findAllFavoritedByUser($user_id)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE r.id IN (
                 SELECT f.recipeId FROM TastetagMainBundle:Favorites f WHERE f.userId = :user_id )')
            ->setParameter('user_id', $user_id)
            ->getResult();

    }

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

    public function findByTags($tags,$number)
    {
          $sql = " 
            SELECT r.id
FROM   recipes r
 INNER JOIN (SELECT   rt.recipe_id
             FROM     recipes_tags rt
                      INNER JOIN recipes r
                        ON r.id = rt.recipe_id
                      INNER JOIN tags t
                        ON t.id = rt.tag_id
             WHERE    t.name IN (".$tags.")
             GROUP BY rt.recipe_id
             HAVING   Count(rt.recipe_id) =".$number.") aa
   ON r.id = aa.recipe_id";


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
}
