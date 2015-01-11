<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\RecipePhoto;


class RecipeType extends AbstractType
{   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('description', 'textarea');
        $builder->add('difficulty');
        $builder->add('preparationTime');
        //$builder->add('RecipePhoto', new RecipePhotoType($this->noFile));
        $builder->add('images', 'collection', array(
            'type' => new RecipePhotoType(),
            'allow_add' => true,
            'by_reference' => false,
            'allow_delete' => true,
            'prototype' => true,
        ));
    }

    public function getName()
    {
        return 'recipe';
    }

}