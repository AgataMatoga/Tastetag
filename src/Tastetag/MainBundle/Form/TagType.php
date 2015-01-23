<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\Tags;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class TagType extends AbstractType
{   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tastetag\MainBundle\Entity\Tags',
        ));
    }

    public function getName()
    {
        return 'tag';
    }
    

}