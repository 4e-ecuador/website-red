<?php

namespace App\Controller;

use App\Entity\Key;
use App\Form\KeyType;
use App\Repository\KeyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/key")
 */
class KeyController extends AbstractController
{
    /**
     * @Route("/", name="key_index", methods={"GET"})
     */
    public function index(KeyRepository $keyRepository): Response
    {
        return $this->render('key/index.html.twig', [
            'keys' => $keyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="key_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $key = new Key();
        $form = $this->createForm(KeyType::class, $key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($key);
            $entityManager->flush();

            return $this->redirectToRoute('key_index');
        }

        return $this->render('key/new.html.twig', [
            'key' => $key,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="key_show", methods={"GET"})
     */
    public function show(Key $key): Response
    {
        return $this->render('key/show.html.twig', [
            'key' => $key,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="key_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Key $key): Response
    {
        $form = $this->createForm(KeyType::class, $key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('key_index');
        }

        return $this->render('key/edit.html.twig', [
            'key' => $key,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="key_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Key $key): Response
    {
        if ($this->isCsrfTokenValid('delete'.$key->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($key);
            $entityManager->flush();
        }

        return $this->redirectToRoute('key_index');
    }
}
