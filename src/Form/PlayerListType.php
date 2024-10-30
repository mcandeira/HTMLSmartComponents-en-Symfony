<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\ExpressionSyntax;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PlayerListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('filter1', TextType::class, [
                'constraints' => [
                    new ExpressionSyntax(allowedVariables: [
                        'names',
                        'reversedNames',
                        'salarys',
                        'reversedSalarys',
                        'hasSalary',
                        '!hasSalary'
                    ]),
                ]
            ])
            ->add('filter2', TextType::class, [
                'constraints' => [
                    new ExpressionSyntax(allowedVariables: [
                        'names',
                        'reversedNames',
                        'salarys',
                        'reversedSalarys',
                        'hasSalary',
                        '!hasSalary'
                    ])
                ]
            ])
            ->add('page', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Positive()
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string{
        return '';
    }
}
