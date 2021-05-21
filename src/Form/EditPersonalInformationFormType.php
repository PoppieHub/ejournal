<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPersonalInformationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Имя'
            ])
            ->add('last_name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('middle_name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label' => 'Отчество'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
