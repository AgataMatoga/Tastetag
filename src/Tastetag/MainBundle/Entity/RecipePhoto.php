<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * RecipePhoto
 *
 * @ORM\Table(name="recipe_photo", indexes={@ORM\Index(name="recipe_id", columns={"recipe_id"})})
 * @ORM\Entity
 */
class RecipePhoto
{
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main_photo", type="boolean", nullable=true)
     */
    private $main_photo;

    /**
     * @var integer
     *
     * @ORM\Column(name="recipe_id", type="integer", nullable=false)
     */
    private $recipeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * var recipe
     * @ORM\ManyToOne(targetEntity="Tastetag\MainBundle\Entity\Recipes", inversedBy="recipe_photo")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     **/
    private $recipe;

    /**
     * Set path
     *
     * @param string $path
     * @return RecipePhoto
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return RecipePhoto
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
     * Set main_photo
     *
     * @param boolean $main_photo
     * @return RecipePhoto
     */
    public function setMainPhoto($main_photo)
    {
        $this->main_photo = $main_photo;

        return $this;
    }

    /**
     * Get main_photo
     *
     * @return boolean 
     */
    public function getMainPhoto()
    {
        return $this->main_photo;
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

    public function addRecipe(\Tastetag\MainBundle\Entity\Recipes $recipe)
    {    
            $this->recipe = $recipe;
    }

    /**
    * Set recipe
    *
    * @param Tastetag\MainBundle\Entity\Recipe $recipe
    */
    public function setRecipe(Tastetag\MainBundle\Entity\Recipe $recipe)
    {
    $this->recipe = $recipe;
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

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }


}
