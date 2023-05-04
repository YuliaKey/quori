<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        $questions = [
            [
                'id' => 1,
                'title' => 'Je suis une question',
                'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquid quos qui debitis iste cumque, libero quasi vero suscipit accusantium quia omnis, eligendi amet quas dolore quis odio possimus vel quisquam iure itaque rem magni minima veritatis aliquam? Sequi, provident. Odit perspiciatis expedita dolor adipisci tempore dignissimos maiores quaerat laboriosam minus?',
                'rating' => 20,
                'author' => [
                    'name' => 'Jean Dupont',
                    'avatar' => 'https://randomuser.me/api/portraits/men/20.jpg'
                ],
                'nbResponse' => 15
            ],
            [
                'id' => 2,
                'title'=> 'je suis une question',
                'content'=> 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officiis, quis odit! Odit earum quisquam ea animi in qui sit quia. Consequatur illum voluptas quidem, sed et                        numquam neque aspernatur quibusdam.',
                'rating'=> 0,
                'author' => [
                    'name'=> 'Laure Joe',
                    'avatar' => 'https://randomuser.me/api/portraits/women/79.jpg'
                ],
                'nbResponse'=> 5
            ],
            [
                'id' => 3,
                'title'=> 'je suis une question',
                'content'=> 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Officiis, quis odit! Odit earum quisquam ea animi in qui sit quia. Consequatur illum voluptas quidem, sed et                        numquam neque aspernatur quibusdam.',
                'rating'=> -15,
                'author' => [
                    'name'=> 'Pascal Praud',
                    'avatar' => 'https://randomuser.me/api/portraits/men/40.jpg'
                ],
                'nbResponse'=> 25
            ]
        ];
        return $this->render('home/index.html.twig', ['questions' => $questions]);
    }
}
