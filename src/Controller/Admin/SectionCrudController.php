<?php

namespace App\Controller\Admin;

use App\Entity\Section;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Section::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        //fields configuration
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('section_name'),
            TextField::new('section_class'),
            
        ];
    }
    
}