<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/hello/{nom}', name: 'app_hello')]
    public function index(Request $request,string $nom): Response
    {   
        $greet = '<h1>Bonjour '.$nom.'</h1>';

        return new Response(
            "<html>
                <body>
                    $greet
                    <img src='/images/under-construction.gif'/>
                </body>
            </hmtl>");
    }
}
