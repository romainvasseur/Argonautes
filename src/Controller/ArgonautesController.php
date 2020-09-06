<?php

namespace App\Controller;

use App\Entity\Argonaute;
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

    private function form() {
        $form = $this->CreateFormBuilder()
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm()
        ;
        return $form;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function Read(Request $request, EntityManagerInterface $em)
    {
        $form = $this->form();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $argonaute = new Argonaute;
            $argonaute->setName($data['name']);
            $em->persist($argonaute);
            $em->flush();
        }
        return $this->render('argonautes/index.html.twig', [
            'argonautes' => $this->repo->findAll(),
            'form' => $form = $this->form()->createView(),
        ]);
    }
}
