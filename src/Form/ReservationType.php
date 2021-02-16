<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Entity\Disponibility;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

//Component constraints Fields
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;



class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_name', TextType::class,[
                'required' => true,
                'label'=> 'Nom'
            ])
            ->add('user_firstname', TextType::class,[
                'required' => true,
                 'label'=> 'Prénom'
            ])
            
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'constraints' =>[
                    new Assert\Email([
                        'message'=> "Le format de l'email n'est pas valide"
                    ])
                ]
            ])
            ->add('user_tel', TextType::class, [
                'required' => true,
                'label' => 'Téléphone',
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
            
            ->add('quantity', IntegerType::class, [
                //unmapped fields
                'mapped' => false,
                'required' => true,
                'label'=> 'Nombre de personne / Séance',
                'attr' => [
                    'min' => '1',
                    'max' => '5'
                ],
            ])
            
             ->add('meetingPlace', ChoiceType::class, [
                 //unmapped fields
                'mapped' => false,
                'required' => true,
                'label' => 'Lieu de rendez-vous',
                'choices'  => [
                    'Rendez-vous au cabinet à Paris 12ème' => 0,
                    'Rendez-vous en ligne' => 1,
                ]
            ])
            
            ->add('disponibility_date', EntityType::class, [
                'label' => 'Date de disponibilité',
                'placeholder' => 'Choisissez une date',
                'class' => Disponibility::class,
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) {
                    //return disponibility (1 -> ok)
                    return $er->createQueryBuilder('d')
                        ->select('d')
                        ->where('d.disponibility_isDispo = 1');
                        // ->setParameter('disponibility_isDispo', 1);
                },
                //Callback function ->return object dispo format 
                'choice_label' => function (Disponibility $disponibility) {
                    return $disponibility->getDisponibilityDate()->format('d-m-Y H:i');
                },
                
            ])
            
            ->add('Suivant', SubmitType::class)
        ;
        /*
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                
                $disponibility = $event->getData();
                $form = $event->getForm();
                
                if($disponibility->getDisponibilityDate()){
                    $form->add('disponibility_date', choiceType::class,['mapped' => false,'required' => true, 'label' => 'Date du rendez-vous']);
                }
                
        });*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //form options 
        ]);
    }
}
