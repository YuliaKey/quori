<?php

namespace App\Controller;

use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'ask_question')]
    public function index(Request $request): Response
    {
        $formQuestion = $this->createForm(QuestionType::class);
        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid()){
        }



        return $this->render('question/index.html.twig', ['form' => $formQuestion->createView()]);
    }

    #[Route('/question/{id}', name: 'show_question')]
    public function show(Request $request, string $id) {
        $question = [
            
                'title' => 'Je suis une question',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid quos qui debitis iste cumque, libero quasi vero suscipit accusantium quia omnis, eligendi amet quas dolore quis odio possimus vel quisquam iure itaque rem magni minima veritatis aliquam? Sequi, provident. Odit perspiciatis expedita dolor adipisci tempore dignissimos maiores quaerat laboriosam minus?',
                'rating' => 20,
                'author' => [
                    'name' => 'Jean Dupont',
                    'avatar' => 'https://randomuser.me/api/portraits/men/20.jpg'
                ],
                'nbResponse' => 15
            ];
        return $this->render('question/show.html.twig', ['question' => $question]);
    }
}
