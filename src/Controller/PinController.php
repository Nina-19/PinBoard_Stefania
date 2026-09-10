<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Entity\User;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PinController extends AbstractController
{
    #[Route('/pin', name: 'app_pin_index')]
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('pin/index.html.twig', [
            'pins' => $pins,
        ]);
    }

    #[Route('/pin/create', name: 'app_pin_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez vous connecter pour créer un pin.');
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        $pin = new Pin();
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($user);

            $entityManager->persist($pin);
            $entityManager->flush();

            $this->addFlash('success', 'Le pin a été créé avec succès.');

            return $this->redirectToRoute('app_pin_index');
        }

        return $this->render('pin/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/pin/{id}', name: 'app_pin_show')]
    public function show(Pin $pin): Response
    {
        return $this->render('pin/show.html.twig', [
            'pin' => $pin,
        ]);
    }

    #[Route('/pin/{id}/edit', name: 'app_pin_edit')]
    public function edit(Pin $pin, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser() || $pin->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier ce pin.');
            return $this->redirectToRoute('app_pin_index');
        }

        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le pin a été modifié avec succès.');

            return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
        }

        return $this->render('pin/edit.html.twig', [
            'form' => $form,
            'pin' => $pin,
        ]);
    }

    #[Route('/pin/{id}/delete', name: 'app_pin_delete')]
    public function delete(Pin $pin, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser() || $pin->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce pin.');
            return $this->redirectToRoute('app_pin_index');
        }

        if ($request->isMethod('POST')) {
            if ($this->isCsrfTokenValid('delete'.$pin->getId(), $request->request->get('_token'))) {
                $entityManager->remove($pin);
                $entityManager->flush();

                $this->addFlash('info', 'Le pin a été supprimé.');
            }

            return $this->redirectToRoute('app_pin_index');
        }

        return $this->render('pin/delete.html.twig', [
            'pin' => $pin,
        ]);
    }
}
