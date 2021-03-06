<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class TagsRepository extends EntityRepository
{
	
    /**
     * Getting all tags ordered by the number of recipes that are tagged with this tag
     * @return void
    */
    public function findAllByRecipesCount()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
	    $qb->select('COUNT(t) cnt, t.id, t.name')
	    ->from('TastetagMainBundle:Tags', 't')
	    ->innerJoin('t.recipes', 'rt')
	    ->groupBy('t.id')
	    ->orderBy('cnt', 'DESC');

	    return $qb->getQuery()->getResult();
	}

	/**
     * Getting all tags by name
     * @param string $keyword keyword
     * @return void
    */
    public function findAllByName($keyword)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT t FROM TastetagMainBundle:Tags t 
                 WHERE LOWER(t.name) LIKE LOWER(:keyword)')
            ->setParameter('keyword', '%'.$keyword.'%')
            ->getResult();
	}
}