<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\Comments;


class CommentType extends AbstractType
{   

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content', 'textarea');
        $builder->add('recipe_id', 'hidden');
    }

    public function getName()
    {
        return 'comment';
    }
    

}