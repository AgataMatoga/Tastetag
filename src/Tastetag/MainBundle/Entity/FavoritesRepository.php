<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FavoritesRepository extends EntityRepository
{
	/**
     * Checking if user favorited certain recipe 
     *
     * @param integer $userId favorite user id
     * @param integer $recipeId recipe id
     * @return void
    */
    public function findAllByUserAndRecipe($userId, $recipeId)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT f FROM TastetagMainBundle:Favorites f WHERE f.userId = :userId AND f.recipeId = :recipeId')
            ->setParameter('userId', $userId)
            ->setParameter('recipeId', $recipeId)
            ->getResult();
	}

	/**
     * Saving favorite
     *
     * @param mixed $recipe commented recipe
     * @param mixed $usr comment user
     * @param mixed $favorite favorite
     * @return void
    */
    public	function saveFavorite($recipe, $usr, $favorite)
	{
	    $em = $this->getEntityManager();
	    $favorite->setRecipe($recipe);
        $favorite->setUser($usr);
        $em->persist($favorite);
        $em->flush();
	}

	/**
     * Removing favorite
     *
     * @param integer $id favorite id
     * @return void
    */
    public	function removeFavorite($id)
	{
	    $em = $this->getEntityManager();
	    $entity = $em->getRepository('TastetagMainBundle:Favorites')->find($id);
        $em->remove($entity);
        $em->flush();
	}
}