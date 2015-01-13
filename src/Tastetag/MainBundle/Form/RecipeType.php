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
        $builder->add('preparationTime');
        $builder->add('difficulty', 'choice', array(
            'choices'   => array(
                '1'   => 'Bardzo łatwy',
                '2' => 'Łatwy',
                '3'   => 'Średni',
                '4' => 'Raczej trudny',
                '5'   => 'Bardzo trudny',
            ),
            'multiple'  => false,
            'expanded'  => true,
        ));
        $builder->add('ingridients', 'collection', array(
            'type'         => new IngridientType(),
            'allow_add'    => true,
            'by_reference' => false,
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