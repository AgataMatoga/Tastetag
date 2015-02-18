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

	public	function saveFavorite($recipe,$usr,$favorite)
	{
	    $em = $this->getEntityManager();
	    $favorite->setRecipe($recipe);
        $favorite->setUser($usr);
        $em->persist($favorite);
        $em->flush();
	}

	public	function removeFavorite($id)
	{
	    $em = $this->getEntityManager();
	    $entity = $em->getRepository('TastetagMainBundle:Favorites')->find($id);
        $em->remove($entity);
        $em->flush();
	}
}