<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        //fields configuration
        return [
            TextField::new('user_name', 'Nom'),
            TextField::new('user_firstname','Prénom'),
            TextField::new('email'),
            textField::new('user_tel', 'Tel'),
            ChoiceField::new('roles')
                ->setChoices([
                    'ADMIN' => 'ROLE_ADMIN',
                    'USER' => 'ROLE_USER'
                    ])->allowMultipleChoices()
            ,
        
           
        ];
    }
    
    
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            //remove the action of deleting a user in this way !! we never know :) !!!
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }
    
}
