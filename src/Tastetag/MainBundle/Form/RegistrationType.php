<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
        	$builder->add('user', new UserType());

	}

        public function getName()
	{
		return 'TastetagMainBundleFormRegistrationType';
	}
}