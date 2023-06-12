<?php

namespace App\Controller\Api;

use App\Entity\ContentContract;
use App\Entity\Contract;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class MainController extends AbstractController
{
    #[Route('/api/', name: 'api_main_index')]
    public function index(#[CurrentUser] User $user): Response
    {
        $permissions = [];

        $today = new DateTime('midnight');

        $filterContracts = fn(Contract $contract) => $today >= $contract->getCreatedAt() && ($today <= $contract->getRemovedAt() || null === $contract->getRemovedAt());
        $filterContents = fn(ContentContract $content) => $today >= $content->getCreatedAt() && ($today <= $content->getRemovedAt() || null === $content->getRemovedAt());

        foreach ($user->getContracts()->filter($filterContracts) as $contract) {
            foreach ($contract->getContents()->filter($filterContents) as $content) {
                $permissions[] = $content->getLabWork()->getSystemName();
            }
        }

        return $this->json([
            'username' => $user->getUsername(),
            'fio' => $user->getEmail(),
            'permissions' => $permissions
        ]);
    }
}