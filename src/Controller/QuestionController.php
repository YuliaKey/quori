<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Question;
use App\Entity\Vote;
use App\Form\CommentType;
use App\Form\QuestionType;
use App\Repository\VoteRepository;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class QuestionController extends AbstractController
{
    #[Route('/question/ask', name: 'ask_question')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $question = new Question();

        $formQuestion = $this->createForm(QuestionType::class, $question);
        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question->setNbResponse(0);
            $question->setRating(0);
            $question->setAuthor($user);
            $question->setCreatedAt(new \DateTimeImmutable(timezone: new DateTimeZone('Europe/Paris')));

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Votre question a ete ajoute');

            return $this->redirectToRoute('home');
        }



        return $this->render('question/index.html.twig', ['form' => $formQuestion->createView()]);
    }

    #[Route('/question/{id}', name: 'show_question')]
    public function show(Request $request, Question $question, EntityManagerInterface $em) {

        $user = $this->getUser();

        $options = [
            'question' => $question
        ];

        if ($user) {

            $comment =  new Comment();
            $commentForm = $this->createForm(CommentType::class, $comment);
            $commentForm->handleRequest($request);
    
            if($commentForm->isSubmitted() && $commentForm->isValid()){
                $comment->setCreatedAt(new \DateTimeImmutable());
                $comment->setRating(0);
                $comment->setQuestion($question);
                $comment->setAuthor($user);
    
                $question->setNbResponse($question->getNbResponse() + 1);
    
                $em->persist($comment);
                $em->flush();
    
                $this->addFlash('success', 'Votre réponse a bien été publié');
    
                return $this->redirect($request->getUri());
            }

            $options['form'] = $commentForm->createView();
        }



        return $this->render('question/show.html.twig', $options);
    }

    #[Route('/question/rating/{id}/{score}', name: 'question_rating')]
    #[IsGranted('IS_AUTHENTICATED_REMEMBERED')]
    public function rate(Request $request, Question $question, int $score, EntityManagerInterface $em, VoteRepository $voteRepository) {
        $currentUser = $this->getUser();

        // je verifie que le current user n'est pas le proprietaire de la question
        if($currentUser != $question->getAuthor()){
            
            // on verifie que le current user a deja vote
            $vote = $voteRepository->findOneBy([
                'author' => $currentUser,
                'question' => $question
            ]);

            if($vote) {
                // s'il avait aime la question et qu'il reclickue sur le like (score = 1), c'est pour enlever son vote
                //OU
                // s'il n'avait pas aimer la question et qu'il reclique sur le dislike c'est pour enlever son vote
                if(($vote->getIsLiked() && $score > 0) || (!$vote->getIsLiked() && $score < 0)) {
                    // on supprime le vote
                    $em->remove($vote);
                    $question->setRating($question->getRating() + ($score > 0 ? -1 : 1));
                } else {
                    $vote->setIsLiked(!$vote->getIsLiked());
                    $question->setRating($question->getRating() + ($score > 0 ? 2 : -2));

                }
            } else {
                // on le lesse voter
                $newVote = new Vote();
                $newVote->setAuthor($currentUser)
                        ->setQuestion($question)
                        ->setIsLiked($score > 0 ? true : false);
                $em->persist($newVote);
                $question->setRating($question->getRating() + $score);
            }

            $em->flush();

        }

        $referer = $request->server->get('HTTP_REFERER');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('home');
    }
}


