<?php

namespace App\Controller\Backend;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/user", name="backend_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/tous-les-utilisateurs", name="show")
     */
    public function show(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('backend/user/show.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, User $user)
    {

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('backend_user_show');
        }

        return $this->render('backend/user/edit.html.twig', [
            'user' => $user,
            'userForm' => $userForm->createView(),
        ]);
    }
}
