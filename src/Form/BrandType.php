<?php

namespace App\Form;

use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => "Brand Name",
                'required' => true
            ])
            ->add('origin', ChoiceType::class,
            [
                'label' => "Brand Origin",
                'required' => true,
                'choices' => [
                    "Vietnam" => "Vietnam",
                    "United States" => "United States",
                    "Japan" => "Japan",
                    "Korea" => "Korea",
                    "China" => "China"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
