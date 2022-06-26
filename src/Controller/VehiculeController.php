<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeType;
use App\Services\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/vehicule")]
class VehiculeController extends AbstractController{

    private $em;
    private $imgService;

    public function __construct(EntityManagerInterface $em, ImageService $imgService)
    {
        $this->em = $em;
        $this->imgService = $imgService;
    }


    #[Route("/list" , name:"vehicule_list")]
    public function list():Response{
       $vehicules = $this->em->getRepository(Vehicule::class)->findAll();
       return $this->render( "vehicule/list.html.twig" , ["vehicules" => $vehicules]);
    }

    #[Route("/update/{id}" , name:"vehicule_update")]
    public function update(Request $request , $id) :Response{
        $vehicule = $this->em->getRepository(Vehicule::class)->find($id);
        if($vehicule === null) return $this->redirectToRoute("vehicule_list"); 

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $file = $form["photo"]->getData();
            if($file){
                $this->imgService->updateImage($file , $vehicule );
            }
           
            $this->em->persist($vehicule);
            $this->em->flush();
            return $this->redirectToRoute("vehicule_list");
        }
        return $this->render("vehicule/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

    #[Route("/supp/{id}" , name:"vehicule_suppr")]
    public function suppr($id) :RedirectResponse{
        $vehiculeASupprimer = $this->em->getRepository(Vehicule::class)->find($id);
        if($vehiculeASupprimer){
           
            $this->imgService->deleteImage($vehiculeASupprimer);
            $this->em->remove($vehiculeASupprimer);
            $this->em->flush();
        }
        return $this->redirectToRoute("vehicule_list");
    }



    #[Route("/new" , name:"vehicule_new")]
    public function new(Request $request  ) :Response{
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $file = $form["photo"]->getData();

            $this->imgService->moveImage($file , $vehicule );

            $this->em->persist($vehicule);
            $this->em->flush();
            return $this->redirectToRoute("vehicule_list");
        }
        return $this->render("vehicule/new.html.twig" , [
            "form" => $form->createView()
        ]);
    }

}