<?php
use Symfony\Component\Validator\Constraints as Assert;

$form = $app['form.factory']->createBuilder('form')
    ->add('name', 'text', array(
        'constraints' => array(
            new Assert\NotBlank(),
        ),
    ))
    ->add('email', 'email', array(
        'constraints' => array(
            new Assert\NotBlank(),
            new Assert\Email(),
        ),
    ))
    ->add('gender', 'choice', array(
        'required' => false,
        'empty_value' => false,
        'attr' => array(
            'class' => 'inline',
        ),
        'choices' => array(
            'male' => 'male',
            'female' => 'female',
        ),
        'data' => 'male',
        'expanded' => true,
        'multiple' => false,
        'constraints' => array(
            new Assert\Choice(array(
                'male',
                'female',
            )),
        ),
    ))
    ->add('favorite_colors', 'choice', array(
        'required' => false,
        'attr' => array(
            'placeholder' => 'Select some colors',
        ),
        'choices' => array(
            'red' => 'red',
            'green' => 'green',
            'blue' => 'blue',
            'white' => 'white',
            'black' => 'black',
        ),
        'expanded' => false,
        'multiple' => true,
    ))
    ->getForm()
;

return $form;
