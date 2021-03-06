<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                ],
            ))
            ->add('first_name', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('middle_name', TextType::class, [
                'label' => 'Отчество'
            ])
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => array(
                    'label' => 'Придумайте пароль',
                ),
                'second_options' => array(
                    'label' => 'Подтвердите пароль',
                ),
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
