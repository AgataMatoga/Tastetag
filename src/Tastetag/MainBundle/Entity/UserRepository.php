<?php 
namespace Tastetag\MainBundle\Entity;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository 
{
    public function saveUser($user, $password) {
        $em = $this->getEntityManager();
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();
    }

    public function loadUserByUsername($username)
    {
        $q = $this
            ->createQueryBuilder('u')
            ->select('u, g')
            ->leftJoin('u.groups', 'g')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();
    }

    public function deactivateUser($userId) {
        $em = $this->getEntityManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(0);

        $em->persist($user);
        $em->flush();
    }

    public function activateUser($userId) {
        $em = $this->getEntityManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(1);

        $em->persist($user);
        $em->flush();
    }

}