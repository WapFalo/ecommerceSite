<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductDiscount;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('percent', PercentType::class, [
                'type' => 'integer',
                'scale' => 0,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
            ])
            ->add('active')
            ->add('products', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
                'query_builder' => function (ProductRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
                'placeholder' => 'Nothing?',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductDiscount::class,
        ]);
    }
}
