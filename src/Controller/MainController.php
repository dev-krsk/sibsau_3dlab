<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index')]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('main.html.twig');
    }
}