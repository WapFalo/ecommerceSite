<?php

namespace App\Controller\Admin;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletter')]
class AdminNewsletterController extends AbstractController
{
    #[Route('/', name: 'app_admin_newsletter_index', methods: ['GET'])]
    public function index(NewsletterRepository $newsletterRepository): Response
    {
        return $this->render('admin/newsletter/index.html.twig', [
            'newsletters' => $newsletterRepository->findAll(),
        ]);
    }

    #[Route('/update/{id}', name: 'app_admin_newsletter_status', methods: ['POST'])]
    public function status(Request $request, Newsletter $newsletter, NewsletterRepository $newsletterRepository): Response
    {
        $newsletter->setActive(!$newsletter->isActive());
        $newsletterRepository->save($newsletter, true);

        return $this->redirectToRoute('app_admin_newsletter_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/add', name: 'app_admin_newsletter_new', methods: ['POST'])]
    public function delete(Request $request, NewsletterRepository $newsletterRepository): Response
    {
        if ($request->request->has('email') && $request->request->get('email') !== '' && $newsletterRepository->findOneBy(['mail' => $request->request->get('email')]) === null) {
            $newsletter = new Newsletter();
            $newsletter->setMail($request->get('email'));
            $newsletter->setActive(true);

            $newsletterRepository->save($newsletter, true);
        }


        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
    }
}
