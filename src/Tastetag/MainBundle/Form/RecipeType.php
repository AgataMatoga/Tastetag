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
        $builder->add('preparationTime');
        $builder->add('difficulty', 'choice', array(
            'choices'   => array('1' => '1', '2' => '2'),
            'required'  => false,
        ));
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
    }

    public function getName()
    {
        return 'recipe';
    }

}