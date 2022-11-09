<?php

namespace App\Controller\Admin;

use App\Entity\ProductsCategory;
use App\Form\ProductsCategoryType;
use App\Repository\ProductsCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/category')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(ProductsCategoryRepository $productsCategoryRepository): Response
    {
        return $this->render('admin/category/index.html.twig', [
            'products_categories' => $productsCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        $productsCategory = new ProductsCategory();
        $form = $this->createForm(ProductsCategoryType::class, $productsCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsCategoryRepository->save($productsCategory, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/category/new.html.twig', [
            'products_category' => $productsCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductsCategory $productsCategory, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        $form = $this->createForm(ProductsCategoryType::class, $productsCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productsCategoryRepository->save($productsCategory, true);

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/category/edit.html.twig', [
            'products_category' => $productsCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, ProductsCategory $productsCategory, ProductsCategoryRepository $productsCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $productsCategory->getId(), $request->request->get('_token'))) {
            $productsCategoryRepository->remove($productsCategory, true);
        }

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
