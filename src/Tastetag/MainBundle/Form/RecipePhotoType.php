<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipePhotoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file');
        $builder->add('main_photo', 'checkbox', array('required' => false));
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
