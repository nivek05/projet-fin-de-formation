<?php

namespace App\Controller;
 
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Section;
use App\Entity\Contact;
use App\Entity\Workshop;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;



class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response{
        
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy( array ('category_name' => 'home'));
        //Retrieve items from the home category
        $articleHome = $this->getDoctrine()->getRepository(Article::class)->findby(array ('category' => $cat) );
        
        return $this->render('home.html.twig', [
            'controller_name' => 'DefaultController',
            'articleHome' => $articleHome
        ]);
    }

    /**
    * @Route("/about", name="about")
    */
    public function about(): Response{
        
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy( array ('category_name' => 'about'));
         //Retrieve items from the home about
        $articleAbout = $this->getDoctrine()->getRepository(Article::class)->findby(array ('category' => $cat) );
        
        return $this->render('about.html.twig', [
          'articleAbout' => $articleAbout
        ]);
    }

    /**
    * @Route("/coaching", name="coaching")
    */
    public function coaching(): Response{ 
        
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy( array ('category_name' => 'coaching'));
        //Retrieve items from the home coaching
        $articleCoaching = $this->getDoctrine()->getRepository(Article::class)->findby(array ('category' => $cat) );
        
        //Retrieves all workshop 
        $workshop = $this->getDoctrine()->getRepository(Workshop::class)->findAll();
        
        //Get the workShop that I don't want to display in the same way in the template
        $workshopOut =  $this->getDoctrine()->getRepository(Workshop::class)->findOneBy(['workshop_name'=>'Coaching individuel']);
        $workshopNameOut=$workshopOut->getWorkshopName();
        
        return $this->render('coaching.html.twig', [
          'articleCoaching' => $articleCoaching,
          'workshops' => $workshop,
          'workshopOut' => $workshopOut,
          'workshopNameOut' => $workshopNameOut
          
        ]);
    }

    /**
    * @Route("/testimony", name="testimony")
    */
    public function testimony(): Response{ 
         
        $cat = $this->getDoctrine()->getRepository(Category::class)->findBy( array ('category_name' => 'testimony'));
        //Retrieve items from the home Testimony
        $articleTestimony = $this->getDoctrine()->getRepository(Article::class)->findby(array ('category' => $cat) );
        
        return $this->render('testimony.html.twig', [
          'articleTestimony' => $articleTestimony
            
        ]);
    }
    
    /**
    * @Route("/legalMention", name="legal-mention")
    */
    public function legalMention(): Response{ 
         
        return $this->render('legalMention.html.twig', [
            
        ]);
    }
    
}