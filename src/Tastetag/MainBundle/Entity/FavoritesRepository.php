<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FavoritesRepository extends EntityRepository
{
	public function findAllByUserAndRecipe($userId, $recipeId)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT f FROM TastetagMainBundle:Favorites f WHERE f.userId = :userId AND f.recipeId = :recipeId')
            ->setParameter('userId', $userId)
            ->setParameter('recipeId', $recipeId)
            ->getResult();
	}
}