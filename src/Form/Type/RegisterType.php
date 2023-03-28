<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            // a password should have at least 8 characters, 1 uppercase and 1 lowercase
            // we need to use the pattern "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$"
            ->add('userPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                // un comment the following option, if you don't want to add a specific field in the entity
                /*'mapped' => false,*/
                'first_options' => [
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Confirm your password'
                ],
                // enable this if you want to use Regex to validate this field without using Asserts in the class
                /*'constraints' => [
                    new Assert\Regex('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/')
                ],*/

                // enable this if you want to use your custom validation to validate this field without using Asserts in the class
                /*'constraints' => [
                    new ValidPassword(),
                    new NotNull(),
                ],*/
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label' => false,
            'validation_groups' => ['registration'],
        ]);
    }
}
