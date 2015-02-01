<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RecipesRepository extends EntityRepository
{
    public function findAllByKeyword($keyword)
    {
        return $this->getEntityManager()
            ->createQuery(
                 'SELECT r FROM TastetagMainBundle:Recipes r WHERE r.name LIKE :keyword')->setParameter('keyword', $keyword)
                //'SELECT p FROM TastetagMainBundle:Recipes p ORDER BY p.name ASC')
            ->getResult();
    }
}