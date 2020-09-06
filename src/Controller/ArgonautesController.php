<?php

namespace App\Controller;

use App\Entity\Argonaute;
use App\Form\ArgonauteType;
use App\Repository\ArgonauteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArgonautesController extends AbstractController
{
    private $repo;

    public function __construct(ArgonauteRepository $repo){
        $this->repo = $repo;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function Read(Request $request, EntityManagerInterface $em)
    {
        $argonaute = new Argonaute;
        $form = $this->createForm(ArgonauteType::class, $argonaute);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($argonaute);
            $em->flush();
            return $this->redirectToRoute('app_index', [
                'argonautes' => $this->repo->findAll(),
                'form' => $form->createView()
            ]);
        }
        return $this->render('argonautes/index.html.twig', [
            'argonautes' => $this->repo->findAll(),
            'form' => $form->createView()
        ]);
    }
}
