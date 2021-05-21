<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserForAdminFromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'Изменить email'
            ))
            ->add('roles', ChoiceType::class, array(
                'label' => 'Изменить роль',
                'multiple' => true,
                'choices' => [
                    'Админ' => "ROLE_ADMIN",
                    'Студент' => "ROLE_STUDENT",
                    'Преподаватель' => "ROLE_TEACHER",
                    'Админ-Студент' => "ROLE_ASTUDENT",
                    'Админ-Преподаватель' => "ROLE_ATEACHER",
                    'Админ-Студент-Преподаватель' => "ROLE_ASTUDENT_ATEACHER",
                ],
            ))
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
