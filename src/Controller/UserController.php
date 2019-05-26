<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/utilisateur", name="user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/nouvels-utilisateur", name="new")
     */
    public function newUser(Request $request, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $newUser = new User();
        $userForm = $this->createForm(UserType::class, $newUser);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $newUser->setRole($roleRepository->findOneBy(['name' => 'user']));

            $hash = $passwordEncoder->encodePassword($newUser, $newUser->getPassword());
            $newUser->setPassword($hash);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUser);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Enregistrement effectué'
            );
            
            return $this->redirectToRoute('home');
        }

        return $this->render('user/new.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash(
                'danger',
                'Suppression effectuée'
            );
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder)
    {

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('user_edit', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'userForm' => $userForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/questions", name="question", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function userQuestion(User $user)
    {
        return $this->render('user/question.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function userInfo(User $user)
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

}
