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
}
