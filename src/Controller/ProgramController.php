<?php


namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Program;

/**
 * @Route("/programs", name="program_")
 */

class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */

    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('programs/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     *
     * @Route("/new", name="new")
     * @param Request $request
     * @param Slugify $slugify
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer) : Response
    {
        // Create a new Program Object
        $program = new Program();
        // Create the associated Form
        $form = $this->createForm(ProgramType::class, $program);
        // Render the form
        $form->handleRequest($request);
        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            // Deal with the submitted data
            // Get the Entity Manager
            $program->setSlug($slugify->generate($program->getTitle()));
            $entityManager = $this->getDoctrine()->getManager();
            // Persist Category Object
            $entityManager->persist($program);
            // Flush the persisted object
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('email/newProgram.html.twig', ['program' => $program]));

            $mailer->send($email);

            // Finally redirect to categories list
            return $this->redirectToRoute('program_index');


        }
        return $this->render('programs/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }


    /**
     * @Route ("/{slug}", methods={"GET"}, name="show")
     * @param Program $program
     * @return Response
     */

    public function show(Program $program): Response
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in program\'s table.'
            );
        }

        return $this->render("programs/show.html.twig", ['program' => $program]);
    }

    /**
     * @Route ("/{program}/season/{season}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}})
     * @param Program $program
     * @param Season $season
     * @return Response
     */

    public function showSeason(Program $program, Season $season): Response
    {

        return $this->render("programs/season_show.html.twig", [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route ("/{program}/seasons/{season}/episode/{episode}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode": "slug"}})
     * @param Program $program
     * @param Season $season
     * @param Episode $episode
     * @param Request $request
     * @return Response
     */

    public function showEpisode(Program $program, Season $season, Episode $episode, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('program_episode_show', [
                'program' => $program->getSlug(),
                'season' => $season->getId(),
                'episode' => $episode->getSlug()
            ]);
        }
        return $this->render("programs/episode_show.html.twig", [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'form' => $form->createView(),
        ]);
    }
}
