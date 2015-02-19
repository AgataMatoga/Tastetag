<?php 
namespace Tastetag\MainBundle\Entity;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository 
{
    /**
     * Saving user
     * @param mixed $user user to save
     * @param string $password password
     * @return void
    */
    public function saveUser($user, $password) {
        $em = $this->getEntityManager();
        $user->setPassword($password);
        $em->persist($user);
        $em->flush();
    }

    /**
     * Loading user by username
     * @param string $username user name
     * @return void
    */
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

    /**
     * Deactivating user account
     * @param integer $userId user to deactivate
     * @return void
    */
    public function deactivateUser($userId) {
        $em = $this->getEntityManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(0);

        $em->persist($user);
        $em->flush();
    }

    /**
     * Activating user account
     * @param integer $userId user to activate
     * @return void
    */
    public function activateUser($userId) {
        $em = $this->getEntityManager();
        $user = $em->getRepository('TastetagMainBundle:User')->find($userId);
        $user->setActive(1);

        $em->persist($user);
        $em->flush();
    }

}