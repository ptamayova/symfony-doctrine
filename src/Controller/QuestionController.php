<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private LoggerInterface $logger;
    private bool $isDebug;
    private QuestionRepository $questionRepository;

    public function __construct(
        LoggerInterface $logger,
        bool $isDebug,
        QuestionRepository $questionRepository
    )
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
        $this->questionRepository = $questionRepository;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('question/homepage.html.twig', [
            'questions' => $this->questionRepository->findAllAskedOrderedByNewest(),
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(): Response
    {
        return new Response('Wow, fixtures are great feature!');
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(
        Question $question,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $direction = $request->request->get('direction');

        if ($direction === 'up') {
            $question->upVote();
        } elseif ($direction === 'down') {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);
    }
}
