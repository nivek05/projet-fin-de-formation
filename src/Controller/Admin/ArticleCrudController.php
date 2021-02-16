<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        //fields configuration
        return [
            IdField::new('id')-> onlyOnIndex(),
            AssociationField::new('category', 'Categories'),
            AssociationField::new('section','Section'),
            TextField::new('article_name', 'Nom article'),
            TextField::new('article_title','Titre article'),
            TextEditorField::new('article_content', 'Contenu article')
                ->setFormType(CKEditorType::class) // Use CKeditor 
            ,
            ImageField::new('article_image', 'image')
                ->setUploadDir('/public/images/pictures') -> onlyOnForms()
                
            ,
            TextField::new('article_sub_title', 'Sous-titre (non obligatoire)')
                -> onlyOnForms(),
        ];
    }
    
    //crud option for the CKeditor theme -> for WYSIWYG use -> article_content
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }
    
}
