<?php

namespace App\Services ;

use App\Entity\Vehicule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService extends AbstractController{

    public function moveImage(UploadedFile $file , Vehicule $vehicule ) :void{
        $dossier_upload = $this->getParameter("upload_directory");
        $photo = md5(uniqid()) . "." . $file->guessExtension(); 
        $file->move( $dossier_upload , $photo  ); 
        $vehicule->setPhoto($photo);
    }

    public function deleteImage(Vehicule $vehicule) :void{
         $dossier_upload = $this->getParameter("upload_directory");
         $photo = $vehicule->getPhoto();
         $oldPhoto = $dossier_upload . "/" . $photo ;
         if(file_exists($oldPhoto)){
             unlink($oldPhoto); 
         }
    }

    public function updateImage(UploadedFile $file ,Vehicule $vehicule) :void{
        $this->deleteImage($vehicule);
        $this->moveImage($file , $vehicule );
    }

}