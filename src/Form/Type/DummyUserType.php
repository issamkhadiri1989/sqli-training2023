<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Model\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * This is a dummy Usee form type used to explain how validate works when there is an inheritance between classes.
 */
class DummyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('age', IntegerType::class)
            ->add('address', DummyAddressType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            /*
             * Uncomment the next line if you want to validate all constraints in the App\Model\User class that are not
             * affected to specific groups. this includes also constraints in the embedded App\Model\Address object and
             * that are not included in a specific groups.
             */
            /*'validation_groups' => ['Default'],*/

            /*
             * Uncomment the next line if you want to validate all constraints in the App\Model\User class that are not
             * affected to specific groups. this includes also constraints in the embedded App\Model\Address that are
             * affected to `User` validation group.
             * Beware, if you use this with App\Model\Admin instead of App\Model\User, then only constraints in the
             * App\Model\User class will be used.
             */
            /*'validation_groups' => ['User'],*/

            /*
             * If no validation groups were specified, then all constraints in the App\Model\User class that are not
             * affected to specific groups will be used and also all constraints in the App\Model\Address class that
             * are not affected to specific groups will also be used.
             * If you are using a child class App\Model\Admin when rendering the form, then all constraints in the
             * class App\Model\Admin will also be used.
             */

            /*
             * When using the child class name as a validation group, all constraints that exist in the App\Model\User class
             * and App\Model\Admin class will be use.
             */
            /*'validation_groups' => ['Admin'],*/

            /*
             * When using specific validation groups, only constraints that are affected to those groups are used.
             * And if you are using inheritance of embedded objects, then all constraints with those specific groups
             * are also used.
             */
            /*'validation_groups' => ['specific_group'],*/
        ]);
    }
}
