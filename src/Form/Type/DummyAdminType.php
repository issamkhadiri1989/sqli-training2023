<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Model\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/*
 * This is a dummy Admin form type used to explain how validate works when there is an inheritance between classes.
 */
class DummyAdminType extends AbstractType
{
    /*
     * Note this function: it is used if you don't want to rewrite all parent's fields. tell symfony just inherit them.
     */
    public function getParent(): string
    {
        return DummyUserType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('secret', TextType::class)
            ->add('public', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Admin::class]);
    }
}
