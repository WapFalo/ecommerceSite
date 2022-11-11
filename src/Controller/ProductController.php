<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_show')]
    public function show(Request $request, ProductRepository $productRepository): Response
    {
        if ($request->query->has('id')) {
            $product = $productRepository->find($request->get('id'));
        } else {
            $product = $productRepository->findOneBy([], ['id' => 'DESC']);
        }

        return $this->render('product.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product_randomize', name: 'app_product_randomize')]
    public function random(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $product = $products[array_rand($products)];

        return $this->redirect($this->generateUrl('app_product_show', ['id' => $product->getId()]));
    }

    #[Route('/products', name: 'app_product_list')]
    public function index(): Response
    {
        return $this->render('allproduct.html.twig');
    }
}
