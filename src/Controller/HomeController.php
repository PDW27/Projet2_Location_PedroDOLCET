<?php 

namespace App\Controller ;

use App\Entity\User;
use App\Entity\Commande;
use App\Entity\Vehicule;
use App\Form\CommandeType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController{

    #[Route("/" , name:"home_index")]
    public function index () :Response{
       
        return $this->render("front/index.html.twig");
    }

    #[Route("/search" , name:"home_search")]
    public function search (Request $request , EntityManagerInterface $em ) :Response{
        $dtDebut = new \DateTime($request->request->get("dt_debut"));
        $dtFin = new \DateTime($request->request->get("dt_fin"));
        $listevehiculeLoue = $em->getRepository(Commande::class)->listeVehiculeLoue($dtDebut ,$dtFin );
        $listevehiculeDisponible = $em->getRepository(Vehicule::class)->findByVehiculeDisponibles( $listevehiculeLoue );
        
        return $this->render("front/resultats.html.twig" , [
            "vehicules" => $listevehiculeDisponible
        ]);
    }

    #[Route("/louer" , name:"home_rent")]
    public function rent(AuthenticationUtils $authenticationUtils){

        $user = new User();
        $formInscription = $this->createForm(RegistrationFormType::class , $user);

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("front/registration.html.twig" , [
            "formInscription" => $formInscription->createView(),
            'last_username' => $lastUsername, 
            'error' => $error
        ]);
    }

    #[Route("/commande" , name:"home_end")]
    public function commande(){

        $commande = new Commande();

        $form = $this->createForm(CommandeType::class , $commande);

        return $this->render("front/commande.html.twig" , [
            "form" => $form->createView()
        ] );
    }

}