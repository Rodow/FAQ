<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/reponse", name="answer_")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Answer $answer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$answer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($answer);
            $entityManager->flush();

            $this->addFlash(
                'danger',
                'Suppression effectuée'
            );
        }

        return $this->redirectToRoute('show', ['slug' => $answer->getQuestion()->getSlug()]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Answer $answer): Response
    {

        $answerForm = $this->createForm(AnswerType::class, $answer);
        $answerForm->handleRequest($request);

        if ($answerForm->isSubmitted() && $answerForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('show', ['slug' => $answer->getQuestion()->getSlug()]);
        }

        return $this->render('answer/edit.html.twig', [
            'answer' => $answer,
            'answerForm' => $answerForm->createView(),
        ]);
    }
}
