<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function show(): Response
    {
        return $this->render('product.html.twig');
    }

    #[Route('/products', name: 'app_product_list')]
    public function index(): Response
    {
        return $this->render('allproduct.html.twig');
    }
}
