<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ProductsCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product_show')]
    public function show(Request $request, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($request->get('id'));
        if ($product) {
            //pass the product to the view
        } else {
            $products = $productRepository->findAll();
            $product = $products[array_rand($products)];
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
    public function index(Request $request, ProductsCategoryRepository $productsCategoryRepository, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $products_length = count($products);

        if ($request->query->has('category')) {
            $products = $productsCategoryRepository->find($request->get('category'))->getProducts();
        }

        $categories = $productsCategoryRepository->findAll();

        return $this->render('allproduct.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'products_length' => $products_length,
        ]);
    }
}
