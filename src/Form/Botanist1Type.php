<?php

namespace App\Form;

use App\Entity\Botanist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Botanist1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('specialization')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('longitude')
            ->add('latitude')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Botanist::class,
        ]);
    }
}
