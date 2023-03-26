<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('cartItems', CollectionType::class, [
            'entry_type' => CartItemType::class,
            'label' => false,
            'allow_delete' => true,
            'allow_add' => true,
            'entry_options' => [
                'label' => false,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
            'label' => false,
            'validation_groups' => ['cart', 'checkout'],
        ]);
    }
}
