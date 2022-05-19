<?php

namespace App\Controller;

use App\Entity\Fruits;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $em): Response
    {
        $fruits = $em->getRepository(Fruits:: class)->findAll();
        return $this->render('fruit/index.html.twig', [
            'fruits'=>$fruits
        ]);
    }

    #[Route('/create', name: 'create_fruit', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $fruitname = trim($request->get('fruitname'));
        $color = trim($request->get('color'));
        $taste = trim($request->get('taste'));
        if (!empty($fruitname && $color)) {
            $entityManager = $doctrine->getManager();
            $fruit = new Fruits();
            $fruit->setFruitname($fruitname);
            $fruit->setColor($color);
            $fruit->setTaste($taste);
            $entityManager->persist($fruit);
            $entityManager->flush();
            return $this->redirectToRoute('homepage');
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    #[Route('/update/{id}', name: 'update_fruit')]
    public function update(Request $request, $id, ManagerRegistry $doctrine): Response
    {
        $fruitname = trim($request->get('fruitname'));
        $color = trim($request->get('color'));
        $taste = trim($request->get('taste'));
        $entityManager = $doctrine->getManager();
        $fruit = $entityManager->getRepository(Fruits::class)->find($id);
       
        $fruit->setFruitname($fruitname);
        $fruit->setColor($color);
        $fruit->setTaste($taste);
        $entityManager->flush();
        return $this->redirectToRoute('homepage');

    }

    #[Route('/delete/{id}', name: 'delete_fruit')]
    public function delete($id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $id = $entityManager->getRepository(Fruits::class)->find($id);
        $entityManager->remove($id);
        $entityManager->flush();
        return $this->redirectToRoute('homepage');
    }
}
