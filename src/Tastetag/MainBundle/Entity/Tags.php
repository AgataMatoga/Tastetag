<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tags
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="Tastetag\MainBundle\Entity\TagsRepository")
 */
class Tags
{
    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Tag must be at least {{ limit }} characters long",
     *      maxMessage = "Tag cannot be longer than {{ limit }} characters long"
     * )
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    /**
     *  @var Collection
     * 
     * @ORM\ManyToMany(targetEntity="Tastetag\MainBundle\Entity\Recipes", inversedBy="tags", cascade={"persist"})
     * @ORM\JoinTable(name="recipe_tags",
     * joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="recipe_id", referencedColumnName="id")}
     * )
     */
    private $recipes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Tags
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Add recipe
     *
     * @param \Tastetag\MainBundle\Entity\Recipes $recipe
     * @return Tags
     */
    public function addRecipe(\Tastetag\MainBundle\Entity\Recipes $recipe)
    {
        $this->recipes[] = $recipe;

        return $this;
    }

    /**
     * Remove recipe
     *
     * @param \Tastetag\MainBundle\Entity\Recipes $recipe
     */
    public function removeRecipe(\Tastetag\MainBundle\Entity\Recipes $recipe)
    {
        $this->recipes->removeElement($recipe);
    }

    /**
     * Get recipe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecipes()
    {
        return $this->recipes;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }
}
