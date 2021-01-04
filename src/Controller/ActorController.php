<?php


namespace App\Controller;


use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActorController
 * @package App\Controller
 * @Route ("/actor", name="actor_")
 */

class ActorController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */

    public function index(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();
        return $this->render('actor/index.html.twig', [
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     * @param string $id
     * @return Response
     */

    public function show(string $id) :Response
    {
        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id' => $id]);

        if (!$actor) {
            throw $this->createNotFoundException(
                'Acteur non trouvé dans notre base de données'
            );
        }

        return $this->render("actor/show.html.twig", [
            'actor' => $actor,
        ]);
    }
}
