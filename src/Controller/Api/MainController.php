<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MainController extends AbstractController
{
    #[Route('/api/', name: 'api_main_index')]
    public function index(#[CurrentUser] User $user): Response
    {
        return $this->json([
            'username' => $user->getUsername()
        ]);
    }
}