<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommentsRepository extends EntityRepository
{
	/**
     * Saving comment
     *
     * @param mixed $usr comment user
     * @param mixed $recipe commented recipe
     * @param mixed $comment comment
     * @return void
    */
	public function saveComment($usr,$recipe,$comment) {
		$em = $this->getEntityManager();
		$comment->setRecipe($recipe);
        $comment->setUser($usr);

        $em->persist($comment);
        $em->flush();
	}
}