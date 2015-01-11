<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipePhotoType extends AbstractType
{
    
    protected $noFile = 1;

    // public function __construct($noFile){
    //     $this->noFile = $noFile;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('path', 'file');
        $builder->add('name','text');
        $builder->add('order', 'number');
        //$builder->add('recipeId');
        // for($i=1;$i<=$this->noFile;$i++){
        //     $builder->add('path'.$i, 'file');
        // };
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tastetag\MainBundle\Entity\RecipePhoto'
        ));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        'data_class' => 'Tastetag\MainBundle\Entity\RecipePhoto',
        );
    }

    public function getName()
    {
        return 'images';
    }
}
