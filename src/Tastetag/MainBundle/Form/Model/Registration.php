<?php
namespace Tastetag\MainBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Tastetag\MainBundle\Entity\User;

class Registration
{
    /**
     * @Assert\Type(type="Tastetag\MainBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

}