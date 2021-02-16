<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

//Component for the constraints on the fields
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('nom', TextType::class, [
                'label' => false,  
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ],
               'constraints' =>[
                    new Assert\Email([
                        'message'=> "Le format de l'email n'est pas valide"
                    ])
                ]
            ])
            
            ->add('telephone', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone'
                ],
                'constraints' => [
                    new Regex(
                        [
                            'pattern' => '/^((\+)33|0|0033)[1-9](\d{2}){4}$/',
                            'message' => "le numero de telephone n'est pas valide"
                        ]
                    ),
                    new Length(
                        [
                            'max' => 10,
                            'maxMessage' => 'le champ est limité à {{ limit }} caractères.'
                            
                        ]
                    )
                ]
                
            ])
            
            ->add('motif', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    'Première consultation coaching' => 'Première consultation coaching',
                    'Gestion du stress - 2h' => 'Gestion du stress - 2h',
                    'Développer la créativité - 3h' => 'Développer la créativité - 3h',
                    'Autre' => 'Autre',
            ],
            'attr' => [
                'placeholder' => 'Email'
            ]])
            
            ->add('message',TextareaType::class,[
               
            'attr' => [
                'placeholder' => 'Votre message ...'
            ]])
            ->add('rgpd',  CheckboxType::class, [
                'attr' => [
                    'class' => 'rgpd'
                ],
               'label' => "J'accepte que mes données personnelles saisies dans ce formulaire soient utilisées dans le cadre d'une prise de contact.",
               'required' => true,
            ])
            
            ->add('Envoyer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
