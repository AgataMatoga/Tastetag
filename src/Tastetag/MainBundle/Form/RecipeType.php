<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\RecipePhoto;
use Tastetag\MainBundle\Entity\Ingridients;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RecipeType extends AbstractType
{   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('description', 'textarea');
        $builder->add('prepMin', 'integer', array('label' => 'Minutes'));
        $builder->add('prepHour', 'integer', array('label' => 'Hours'));
        $builder->add('people', 'integer', array('label' => 'Portions'));
        $builder->add('difficulty', 'choice', array(
            'choices'   => array(
                '1'   => 'Bardzo łatwy',
                '2' => 'Łatwy',
                '3'   => 'Średni',
                '4' => 'Raczej trudny',
                '5'   => 'Trudny',
            )
        ));
        $builder->add('ingridients', 'collection', array(
            'type'         => new IngridientType(),
            'allow_add'    => true,
            'by_reference' => false,
            'allow_delete' => true,
        ));
        $builder->add('images', 'collection', array(
            'type'         => new RecipePhotoType(),
            'allow_add'    => true,
            'by_reference' => false,
            'allow_delete' => true,
        ));
         $builder->add('tags', 'collection', array(
            'type'         => new TagType(),
            'allow_add'    => true,
            'by_reference' => false,
            'allow_delete' => true,
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tastetag\MainBundle\Entity\Recipes',
        ));
    }


    public function getName()
    {
        return 'recipe';
    }

}