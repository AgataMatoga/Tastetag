<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\Length(
     *      min = 6,
     *      max = 50,
     *      minMessage = "Your name must be at least {{ limit }} characters long",
     *      maxMessage = "Your name cannot be longer than {{ limit }} characters long"
     * )
     * @ORM\Column(type="string", length=45)
     */
    protected $username;

    /**
     * @Assert\Length(
     *      min = 7,
     *      max = 50,
     *      minMessage = "Password must be at least {{ limit }} characters long",
     *      maxMessage = "Password cannot be longer than {{ limit }} characters long"
     * )
     * @ORM\Column(type="string", length=255)
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $salt;

     /**
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Comments", mappedBy="user")
     */

    protected $comments;

     /**
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Favorites", mappedBy="user")
     */

    protected $favorites;

    /**
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Recipes", mappedBy="user")
     */

    protected $recipes;

    protected $admin;

    protected $active;


    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
        $this->admin = false;
        $this->active = true;
    }

    public function getRoles()
    {       
        if($this->admin) {
            return array('ROLE_ADMIN');
        } else {
            return array('ROLE_USER');
        }
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    
    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function eraseCredentials() {}

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->password,
            $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }

    public function addComment(\Tastetag\MainBundle\Entity\Comments $comments)
    {
      $this->comments[] = $comments;
    }

    public function getComments()
    {
      return $this->comments;
    }

    public function addRecipe(\Tastetag\MainBundle\Entity\Recipes $recipes)
    {
      $this->recipes[] = $recipes;
    }

    public function getRecipes()
    {
      return $this->recipes;
    }

    public function addFavorite(\Tastetag\MainBundle\Entity\Favorites $favorites)
    {
      $this->favorites[] = $favorites;
    }

    public function getFavorites()
    {
      return $this->favorites;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return User
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

     /**
     * Set active
     *
     * @param boolean $active
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

     public function isEnabled()
    {
        return $this->active;
    }

}
