<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorites
 */
class Favorites
{

    private $recipeId;

    private $userId;

    private $id;

    protected $recipe;

    /**
     * var user
     * @ORM\ManyToOne(targetEntity="Tastetag\MainBundle\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/

    protected $user;

    /**
     * Set recipeId
     *
     * @param integer $recipeId
     * @return Favorites
     */
    public function setRecipeId($recipeId)
    {
        $this->recipeId = $recipeId;

        return $this;
    }

    /**
     * Get recipeId
     *
     * @return integer 
     */
    public function getRecipeId()
    {
        return $this->recipeId;
    }

    /**
     * Set recipe
     *
     * @param Tastetag\MainBundle\Entity\Recipe $recipe
     */
    public function setRecipe(\Tastetag\MainBundle\Entity\Recipes $recipe)
    {
        $this->recipe = $recipe;
        $this->recipe->addFavorite($this);
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

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Favorites
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set user
     *
     * @param Tastetag\MainBundle\Entity\User $user
     */
    public function setUser(\Tastetag\MainBundle\Entity\User $user)
    {
        $this->user = $user;
        $this->user->addFavorite($this);
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
