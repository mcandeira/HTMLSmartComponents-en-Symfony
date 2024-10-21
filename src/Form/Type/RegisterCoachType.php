<?php

namespace App\Form\Type;

use App\Entity\Club;
use App\Entity\Coach;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class RegisterCoachType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('coach', EntityType::class, [
                'class' => Coach::class,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('salary', MoneyType::class, [
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
