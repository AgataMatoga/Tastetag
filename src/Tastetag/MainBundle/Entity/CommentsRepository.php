<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommentsRepository extends EntityRepository
{
	public function saveComment($usr,$recipe,$comment) {
		$em = $this->getEntityManager();
		$comment->setRecipe($recipe);
        $comment->setUser($usr);

        $em->persist($comment);
        $em->flush();
	}
}