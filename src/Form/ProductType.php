<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductsCategory;
use App\Repository\ProductsCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('sku', IntegerType::class, [
                'label' => 'SKU',
                'attr' => [
                    'min' => 50,
                    'max' => 99999999,
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Price',
            ])
            ->add('image', UrlType::class, [
                'label' => 'Image URL',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => ProductsCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (ProductsCategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'placeholder' => 'None',
                'required' => false,
            ])
            ->add('quantity', IntegerType::class, [
                'data' => $options['data']->getInventory()?->getQuantity(),
                'attr' => [
                    'min' => 0,
                    'max' => 99999999,
                ],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
