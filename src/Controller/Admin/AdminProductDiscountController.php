<?php

namespace App\Controller\Admin;

use App\Entity\ProductDiscount;
use App\Form\ProductDiscountType;
use App\Repository\ProductDiscountRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product/discount')]
class AdminProductDiscountController extends AbstractController
{
    #[Route('/', name: 'app_admin_product_discount_index', methods: ['GET'])]
    public function index(ProductDiscountRepository $productDiscountRepository): Response
    {
        return $this->render('admin/product_discount/index.html.twig', [
            'product_discounts' => $productDiscountRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_product_discount_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductDiscountRepository $productDiscountRepository, ProductRepository $productRepository): Response
    {
        $productDiscount = new ProductDiscount();
        $form = $this->createForm(ProductDiscountType::class, $productDiscount);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($productDiscount->getProducts() as $product) {
                $product->addDiscount($productDiscount);
                $productRepository->save($product, true);
            }
            $productDiscountRepository->save($productDiscount, true);

            return $this->redirectToRoute('app_admin_product_discount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product_discount/new.html.twig', [
            'product_discount' => $productDiscount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_product_discount_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductDiscount $productDiscount, ProductDiscountRepository $productDiscountRepository, ProductRepository $productRepository): Response
    {
        foreach ($productDiscount->getProducts() as $product) {
            $product->removeDiscount($productDiscount);
            $productRepository->save($product, true);
        }

        $form = $this->createForm(ProductDiscountType::class, $productDiscount);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($productDiscount->getProducts() as $product) {
                $product->addDiscount($productDiscount);
                $productRepository->save($product, true);
            }
            $productDiscountRepository->save($productDiscount, true);

            return $this->redirectToRoute('app_admin_product_discount_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product_discount/edit.html.twig', [
            'product_discount' => $productDiscount,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_product_discount_delete', methods: ['POST'])]
    public function delete(Request $request, ProductDiscount $productDiscount, ProductDiscountRepository $productDiscountRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $productDiscount->getId(), $request->request->get('_token'))) {
            $productDiscountRepository->remove($productDiscount, true);
        }

        return $this->redirectToRoute('app_admin_product_discount_index', [], Response::HTTP_SEE_OTHER);
    }
}
