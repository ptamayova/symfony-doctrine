<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function new(EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setName('Missing pants')
            ->setSlug('missing-pants-' . rand(0, 1000))
            ->setQuestion(<<<EOF
                Hi! So... I'm having a *weird* day. Yesterday, I cast a spell
                to make my dishes wash themselves. But while I was casting it,
                I slipped a little and I think `I also hit my pants with the spell`.
                When I woke up this morning, I caught a quick glimpse of my pants
                opening the front door and walking out! I've been out all afternoon
                (with no pants mind you) searching for them.
                Does anyone have a spell to call your pants back?
                EOF
            )
            ->setVotes(rand(-20, 50));

            if (rand(1, 10) > 2) {
                $question->setAskedAt(new DateTime(sprintf('-%d days', rand(1, 100))));
            }

        // Persists the question in the DB
        $entityManager->persist($question);
        $entityManager->flush();

        return new Response(sprintf(
            'Well hallo! The shiny new question is id #%d, slug: %s',
            $question->getId(),
            $question->getSlug()
        ));
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager)
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
