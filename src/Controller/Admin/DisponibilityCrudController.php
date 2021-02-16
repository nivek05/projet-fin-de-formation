<?php

namespace App\Controller\Admin;

use App\Entity\Disponibility;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class DisponibilityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Disponibility::class;
    }

    
     public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateTimeField::new('disponibility_date'),
            BooleanField::new('disponibility_isDispo'),
        ];
    }
}
