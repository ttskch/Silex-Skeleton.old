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
    ->add('sex', 'choice', array(
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
    ->getForm()
;

return $form;
