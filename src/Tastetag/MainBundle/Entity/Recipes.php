<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Recipes
 *
 * @ORM\Table(name="recipes")
 * @ORM\Entity
 */
class Recipes
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="difficulty", type="integer", nullable=true)
     */
    private $difficulty;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="preparation_time", type="time", nullable=true)
     */
    private $preparationTime;

     /**
     * @var integer
     *
     * @ORM\Column(name="prep_min", type="integer", nullable=false)
     */
    private $prepMin;

       /**
     * @var integer
     *
     * @ORM\Column(name="prep_hour", type="integer", nullable=false)
     */
    private $prepHour;

       /**
     * @var integer
     *
     * @ORM\Column(name="people", type="integer", nullable=false)
     */
    private $people;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

     /**
     * @ORM\OneToMany(targetEntity="Comments", mappedBy="recipe")
     */

    protected $comments;

     /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\RecipePhoto", mappedBy="recipe")
     */
    protected $images;

     /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Ingridients", mappedBy="recipe")
     */
    protected $ingridients;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ingridients = new ArrayCollection();
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Recipes
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
     * Set name
     *
     * @param string $name
     * @return Recipes
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
     * Set prepMin
     *
     * @param string $prepMin
     * @return Recipes
     */
    public function setPrepMin($prepMin)
    {
        $this->prepMin = $prepMin;

        return $this;
    }

    /**
     * Get prepMin
     *
     * @return string 
     */
    public function getPrepMin()
    {
        return $this->prepMin;
    }

    /**
     * Set prepHour
     *
     * @param string $prepHour
     * @return Recipes
     */
    public function setPrepHour($prepHour)
    {
        $this->prepHour = $prepHour;

        return $this;
    }

    /**
     * Get prepHour
     *
     * @return string 
     */
    public function getPrepHour()
    {
        return $this->prepHour;
    }

    /**
     * Set people
     *
     * @param string $people
     * @return Recipes
     */
    public function setPeople($people)
    {
        $this->people = $people;

        return $this;
    }

    /**
     * Get people
     *
     * @return string 
     */
    public function getPeople()
    {
        return $this->people;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Recipes
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set difficulty
     *
     * @param integer $difficulty
     * @return Recipes
     */
    public function setDifficulty($difficulty)
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    /**
     * Get difficulty
     *
     * @return integer 
     */
    public function getDifficulty()
    {
        return $this->difficulty;
    }

    /**
     * Set preparationTime
     *
     * @param \DateTime $preparationTime
     * @return Recipes
     */
    public function setPreparationTime($preparationTime)
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    /**
     * Get preparationTime
     *
     * @return \DateTime 
     */
    public function getPreparationTime()
    {
        return $this->preparationTime;
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

    public function addComment(\Tastetag\MainBundle\Entity\Comments $comments)
    {
      $this->comments[] = $comments;
    }

    public function getComments()
    {
      return $this->comments;
    }

    public function addIngridient(\Tastetag\MainBundle\Entity\Ingridients $ingridient)
    {
        $ingridient->addRecipe($this);
        $this->ingridients[] = $ingridient;
    }

    public function getIngridients()
    {
      return $this->ingridients;
    }

    public function removeIngridient(\Tastetag\MainBundle\Entity\Ingridients $ingridient)
    {
        $this->ingridients->removeElement($ingridient);
    }

    public function addImage(\Tastetag\MainBundle\Entity\RecipePhoto $image)
    {
        $image->addRecipe($this);
        $this->images[] = $image;
    }

    public function getImages()
    {
        return $this->images;
    }

    public function removeImage(\Tastetag\MainBundle\Entity\RecipePhoto $image)
    {
        $this->images->removeElement($image);
    }

}
