<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use http\Client\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    private AnswerRepository $answerRepository;

    /**
     * @param AnswerRepository $answerRepository
     */
    public function __construct(AnswerRepository $answerRepository)
    {
        $this->answerRepository = $answerRepository;
    }

    /**
     * @Route("/answers/popular", name="app_popular_answers", methods={"GET"})
     */
    public function popularAnswers()
    {
        try {
            $answers = $this->answerRepository->findMostPopular();
        } catch (Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }

        return $this->render('answer/popularAnswers.html.twig', [
            'answers' => $answers,
        ]);
    }

    /**
     * @Route("/answers/{id}/vote", methods="POST", name="app_answer_vote")
     */
    public function answerVote(
        Answer $answer,
        LoggerInterface $logger,
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $direction = $data['direction'] ?? 'up';

        // use real logic here to save this to the database
        if ($direction === 'up') {
            $logger->info('Voting up!');
            $answer->setVotes($answer->getVotes() + 1);
            $currentVoteCount = rand(7, 100);
        } else {
            $logger->info('Voting down!');
            $answer->setVotes($answer->getVotes() - 1);
        }

        $entityManager->flush();

        return $this->json(['votes' => $answer->getVotes()]);
    }
}
