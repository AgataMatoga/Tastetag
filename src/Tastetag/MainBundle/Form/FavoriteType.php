<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\Comments;


class FavoriteType extends AbstractType
{   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function getName()
    {
        return 'favorite';
    }

}