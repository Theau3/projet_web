<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\NewUsernameType;
use App\Form\Type\NewPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\Type\NewProfilePictureType;

class ParametresController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }


    #[Route('/parametres', name: 'app_parametres')]
    public function parametres(Request $request,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $newUser = new User();

        $formUsername = $this->createForm(NewUsernameType::class, $newUser,[
            'action' => $this->generateUrl('app_parametres'),
            'method' => 'GET',
        ]);
        $formUsername->handleRequest($request);
        
        $formPassword = $this->createForm(NewPasswordType::class, $newUser,[
            'action' => $this->generateUrl('app_parametres'),
            'method' => 'GET',
        ]);
        $formPassword->handleRequest($request);
        
        $formProfilePicture = $this->createForm(NewProfilePictureType::class, $newUser,[
            'action' => $this->generateUrl('app_parametres'),
            'method' => 'GET',
        ]);
        $formProfilePicture->handleRequest($request);

        if ($formUsername->isSubmitted() ) {
            if($formUsername->isValid() ){
                $newUser = $formUsername->getData();
                $user->setUsername($newUser->getUsername());
                $entityManager->flush();
                return $this->redirect('/home/parametres?tab=NomUtilisateur?form=ok');
            }
            return $this->redirect('/home/parametres?tab=NomUtilisateur?form=error');
        }


        if ($formPassword->isSubmitted() ) {
            if($formPassword->isValid()){
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $formPassword->get('plainPassword')->getData()
                    )
                );
                $entityManager->flush();
                return $this->redirect('/home/parametres?tab=MotDePasse?form=ok');
            }
            return $this->redirect('/home/parametres?tab=MotDePasse?form=error');
        }

        if ($formProfilePicture->isSubmitted() ) {
            if($formProfilePicture->isValid()){
                $user->setPp($newUser->getPp());
                $entityManager->flush();
                return $this->redirect('/home/parametres?tab=PhotoDeProfil?form=ok');
            }
            return $this->redirect('/home/parametres?tab=PhotoDeProfil?form=error');
        }

        $url = $_SERVER['REQUEST_URI'];

        return $this->renderForm('parametres.html.twig',[
                            'formUsername' => $formUsername,
                            'formPassword' => $formPassword,
                            'formProfilePicture' => $formProfilePicture,
                            'url' => $url,
        ]);
    }
}