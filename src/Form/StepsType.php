<?php

namespace App\Form;

use App\Entity\Dance;
use App\Entity\Steps;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StepsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('m1_x')
            ->add('m1_y')
            ->add('m1_toe')
            ->add('m1_heel')
            ->add('m1_rotate')
            ->add('m2_x')
            ->add('m2_y')
            ->add('m2_toe')
            ->add('m2_heel')
            ->add('m2_rotate')
            ->add('dance_id', EntityType::class, [
                'class' => Dance::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Steps::class,
        ]);
    }
}
