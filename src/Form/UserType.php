<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

//Component pour les contraintes sur les champs
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('user_name', TextType::class,[
                'label'=> 'Nom',
                'required' => true
            ])
            ->add('user_firstname',TextType::class,[
                'label'=> 'Prénom',
                'required' => true
               
            ])
            ->add('email', TextType::class,[
                'label'=> 'Email',
                'required' => true
            ])
            ->add('user_tel', TextType::class,[
                'label'=> 'Téléphone',
                'required' => true
            ])
            ->add('pwd', RepeatedType::class,[
                'mapped' => false,
                'required' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les 2 champs doivent correspondre',
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Comfirmation Mot de passe'],
            ])
            ->add('Modifier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
