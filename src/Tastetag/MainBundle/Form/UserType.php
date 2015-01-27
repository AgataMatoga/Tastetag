<?php

namespace Tastetag\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Tastetag\MainBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->add('username', 'text', array('label' => false, 'attr'=> array('class'=>'form-control', 'placeholder' => 'Login')));
    $builder->add('password', 'repeated', array(
              'first_name'  => 'password',
              'second_name' => 'confirm',
              'type'        => 'password',
              'options' => array('label' => false),
              'first_options'  => array('attr'=> array('class'=>'form-control', 'placeholder' => 'Password')),
              'second_options' => array('attr'=> array('class'=>'form-control', 'placeholder' => 'Repeat Password')),
          ));

    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array('data_class' => 'Tastetag\MainBundle\Entity\User'));
	}

  public function getName()
	{
		return 'TastetagMainBundleFormUserType';
	}
}