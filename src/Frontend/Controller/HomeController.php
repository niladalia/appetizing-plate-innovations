<?php

namespace App\Frontend\Controller;

use App\Shared\Controller\BaseController;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends BaseController
{
    #[Route('/app/', name: 'app_home', methods: ['GET'])]
    public function index()
    {
        return $this->render('@frontend/index.html.twig', [
        ]);
    }
}
