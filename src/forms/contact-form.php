<?php
use Symfony\Component\Validator\Constraints as Assert;

$form = $app['form.factory']->createBuilder('form')
    ->add('name', 'text', array(
        'attr' => array(
            'autofocus' => true,
        ),
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
    ->add('interesting_services', 'choice', array(
        'required' => false,
        'attr' => array(
            'placeholder' => 'Multiple selection',
        ),
        'choices' => array(
            'Service A' => 'Service A',
            'Service B' => 'Service B',
            'Service C' => 'Service C',
        ),
        'expanded' => false,
        'multiple' => true,
    ))
    ->add('message', 'textarea', array(
        'attr' => array(
            'rows' => 5,
        ),
    ))
    ->getForm()
;

return $form;
