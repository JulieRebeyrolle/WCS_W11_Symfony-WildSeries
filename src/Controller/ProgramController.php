<?php


namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route ("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
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
     * @Route ("/{program}/seasons/{season}", name="season_show")
     * @param Program $program
     * @param Season $season
     * @return Response
     */

    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season_id' => $season]);

        return $this->render("programs/season_show.html.twig", [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route ("/{program}/seasons/{season}/episode/{episode}", name="episode_show")
     * @param Program $program
     * @param Season $season
     * @param Episode $episode
     * @return Response
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render("programs/episode_show.html.twig", [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }
}
