<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/recipe/new', name: 'recipe.new')]
    public function index(Recipe $recipe = null, Request $request, RecipeRepository $repo): Response
    {
        if(!$recipe){
            $recipe = new Recipe();
        }
        
        $formBuilder = $this->createFormBuilder($recipe);
        $formBuilder
            ->add('title')
            ->add('submit',SubmitType::class)
        ;
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($data);
            $manager->flush();

            return $this->redirectToRoute("recipe.new");
        }

        $recipeList = $repo->findAll();

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
            'recipeList' => $recipeList
        ]);
    }
}
