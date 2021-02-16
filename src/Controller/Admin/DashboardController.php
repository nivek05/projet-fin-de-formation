<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\User;
use App\Entity\Section;
use App\Entity\Reservation;
use App\Entity\Workshop;
use App\Entity\Disponibility;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
   
    /**
     * @Route("/admin-home", name="admin")
     */
    public function index(): Response
    {
         return $this->render('Admin/easyAdminBundle/index.html.twig', [
            'controller_name' => 'DashboardController',
            //'user' =>[]
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BackEnd - Actuel Coaching');
    }

    public function configureMenuItems(): iterable
    {
        //Configuration of menu
        yield MenuItem::linktoDashboard('Menu', 'fa fa-home');
        yield MenuItem::linkToCrud('Article', 'fas fa-list', Article::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-list', Contact::class);
        yield MenuItem::linkToCrud('Section', 'fas fa-list', Section::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Atelier', 'fas fa-list', Workshop::class);
        yield MenuItem::linkToCrud('Reservation', 'fas fa-list', Reservation::class);
        yield MenuItem::linkToCrud('Disponibilité', 'fas fa-list', Disponibility::class);
    }
    
   
    
}
