<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        //fields configuration
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('reservation_info_rdv', 'Info lieu RDV')->setChoices( [ 'Rendez-vous au cabinet' => 0, 'Rendez-vous en ligne' => 1 ]),
            DateField::new('reservation_date','Date de resa'),
            IntegerField::new('reservation_pers_quantity', 'Nombre de personne'),
            IntegerField::new('reservation_total_price', 'Prix total en Eur'),
            AssociationField::new('workshop','ID atelier')-> onlyOnIndex(),
            AssociationField::new('user','ID user')
            -> onlyOnIndex()
            ->formatValue(
                //get value as argument
                function ($value) {
                    //return user deleted if value is null
                return $value === null ? sprintf('compte user supprimé', 1) : $value;
            }),
            AssociationField::new('disponibility','ID Dispo')-> onlyOnIndex(),
        ];
    }
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //remove the action of deleting a reservation in this way !! we never know :) !!!
            //allows you to keep track of reservations even if the user has deleted their account
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }
    
}
