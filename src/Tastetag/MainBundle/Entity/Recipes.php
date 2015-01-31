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
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Comments", mappedBy="recipe")
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="Tastetag\MainBundle\Entity\Favorites", mappedBy="recipe")
     */
    protected $favorites;

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
    
    /**
     *  @var Collection
     * 
     * @ORM\ManyToMany(targetEntity="Tastetag\MainBundle\Entity\Tags", inversedBy="recipes", cascade={"persist"})
     * @ORM\JoinTable(name="recipe_tags",
     * joinColumns={@ORM\JoinColumn(name="recipe_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;

     /**
     * var user
     * @ORM\ManyToOne(targetEntity="Tastetag\MainBundle\Entity\User", inversedBy="recipes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/

    protected $user;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ingridients = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function getDiffDescr()
    {   
        $diffDescr = "";
        if ($this->difficulty == 1) {
            $diffDescr = "Bardzo łatwy";
        } elseif ($this->difficulty == 2) {
            $diffDescr = "Łatwy";
        }
        elseif ($this->difficulty == 3) {
            $diffDescr = "Średni";
        }
        elseif ($this->difficulty == 4) {
            $diffDescr = "Trudny";
        }
        elseif ($this->difficulty == 5) {
            $diffDescr = "Bardzo trudny";
        }
        return $diffDescr;
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

    public function addFavorite(\Tastetag\MainBundle\Entity\Favorites $favorites)
    {
      $this->favorites[] = $favorites;
    }

    public function getFavorites()
    {
      return $this->favorites;
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

    public function addTag(\Tastetag\MainBundle\Entity\Tags $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    public function removeTag(\Tastetag\MainBundle\Entity\Tags $tag)
    {
        $this->tags->removeElement($tag);
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function getTag($tagId)
    {
        foreach ($this->tags as $tag) {
            if ($tag->getId() == $tagId) {
                return $tag;
            }
        }    
    }

     /**
     * Set user
     *
     * @param Tastetag\MainBundle\Entity\User $user
     */
    public function setUser(\Tastetag\MainBundle\Entity\User $user)
    {
        $this->user = $user;
        $this->user->addRecipe($this);
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

    public function isLikedByUser($user_id)
    {   
        $check = false;
        $favorites = $this->favorites->toArray();
        foreach($favorites as $favorite ) {
            if($favorite->getUserId() == $user_id) {
                $check = true;
            }
        }
        return $check;
    }
}
