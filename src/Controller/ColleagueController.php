<?php

namespace App\Controller;

use App\Entity\Colleague;
use App\Form\ColleagueType;
use App\Repository\ColleagueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/colleague')]
class ColleagueController extends AbstractController
{
    #[Route('/', name: 'app_colleague_index', methods: ['GET'])]
    public function index(ColleagueRepository $colleagueRepository): Response
    {
        return $this->render('colleague/index.html.twig', [
            'colleagues' => $colleagueRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_colleague_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $colleague = new Colleague();
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($colleague);
            $entityManager->flush();

            return $this->redirectToRoute('app_colleague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('colleague/new.html.twig', [
            'colleague' => $colleague,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_colleague_show', methods: ['GET'])]
    public function show(Colleague $colleague): Response
    {
        return $this->render('colleague/show.html.twig', [
            'colleague' => $colleague,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_colleague_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Colleague $colleague, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ColleagueType::class, $colleague);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_colleague_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('colleague/edit.html.twig', [
            'colleague' => $colleague,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_colleague_delete', methods: ['POST'])]
    public function delete(Request $request, Colleague $colleague, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$colleague->getId(), $request->request->get('_token'))) {
            $entityManager->remove($colleague);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_colleague_index', [], Response::HTTP_SEE_OTHER);
    }
}
