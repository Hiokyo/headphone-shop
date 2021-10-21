<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
            [
                'label' => "Product Name",
                'required' => true
            ])
            ->add('description', TextType::class,
            [   
                'label' => "Product Description",
                'required' => true
            ])
            ->add('price', MoneyType::class,
            [
                'label' => "Product Price",
                'required' => true,
                'currency' => "USD"
            ])
            ->add('image', FileType::class,
            [
                'label' => "Product Image",
                'data_class' => null,
                'required' => is_null($builder->getData()->getImage())
            ])
            ->add('brand', EntityType::class,
            [
                'class' => Brand::class,
                'choice_label' => "name",
                'multiple' => false,
                'expanded' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
