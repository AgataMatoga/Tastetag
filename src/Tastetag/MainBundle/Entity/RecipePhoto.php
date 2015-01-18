<?php

namespace Tastetag\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

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
    * @var File $file
    *
    * @Assert\File(
    *     maxSize="1M",
    *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
    * )
    */

    protected $file;

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

     /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
        return 'uploads/images';
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
       // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $this->path = $this->getFile()->getClientOriginalName();

        // clean up the file property as you won't need it anymore
        $this->file = null;
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
