<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Store;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function __construct(private readonly Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $connectedUser */
        $connectedUser = $this->security->getUser();
        $ownedStores = $connectedUser->getStores();

        $builder->add('name', TextType::class)
            ->add('shortDescription', TextareaType::class)
            ->add('fullDescription', TextareaType::class)
            ->add('unitPrice', TextType::class)
            ->add('quantity', IntegerType::class)
            ->add('store', EntityType::class, [
                'class' => Store::class,
                // here we can use a callable to customize the way we display choices
                'choice_label' => function (Store $arg) {
                    // do something if you want
                    $name = \strtoupper($arg->getName());

                    return $name;
                },
                'choices' => $ownedStores,
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
//                'choice_label' => function (Category $c) {
//                    return $c->getId() . ' ' . $c->getSlug();
//                },
//                'multiple' => true,
//                'expanded' => true,
            ])
            // the following field wont be mapped but we still can use it
            ->add('test', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'key1' => 'value1',
                    'key2' => 'value2',
                    'key3' => 'value3',
                ],
//                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
