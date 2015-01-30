<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 */
class Comments
{
    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $content;
    
    protected $recipeId;
    
    private $userId;

    /**
     * @var integer
     */
    private $id;

    protected $recipe;

    /**
     * var user
     * @ORM\ManyToOne(targetEntity="Tastetag\MainBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/

    protected $user;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }


    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Comments
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Comments
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
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
     * Set recipe
     *
     * @param Tastetag\MainBundle\Entity\Recipe $recipe
     */
    public function setRecipe(\Tastetag\MainBundle\Entity\Recipes $recipe)
    {
        $this->recipe = $recipe;
        $this->recipe->addComment($this);
    }

    /**
     * Get recipe
     *
     * @return Tastetag\MainBundle\Entity\Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    public function setRecipeId($recipe_id)
    {
        $this->recipeId = $recipe_id;
        return $this;
    }

    public function getRecipeId()
    {
        return $this->recipeId;
    }

    /**
     * Set user
     *
     * @param Tastetag\MainBundle\Entity\User $user
     */
    public function setUser(\Tastetag\MainBundle\Entity\User $user)
    {
        $this->user = $user;
        $this->user->addComment($this);
    }

    /**
     * Get user
     *
     * @return Tastetag\MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUserId($user_id)
    {
        $this->userId = $user_id;
        return $this;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
