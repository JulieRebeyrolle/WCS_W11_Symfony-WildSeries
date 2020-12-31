<?php


namespace App\Controller;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{

    /**
     * @Route({
     *     "en": "/my-profile",
     *     "fr": "/mon-compte"
     * }, name="my-profile")
     * @return Response
     */
    public function showProfile() : Response
    {
        $user = $this->getUser();

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);

    }

}