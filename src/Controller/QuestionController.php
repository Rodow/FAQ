<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/question", name="question_")
 */
class QuestionController extends AbstractController
{

    /**
     * @Route("/poser-une-question", name="new")
     */
    public function newQuestion(Request $request, UserRepository $userRepository) // Route à protéger en yaml et rediriger vers la connexion
    {
        $newQuestion = new Question();
        $questionForm = $this->createForm(QuestionType::class, $newQuestion);
        $questionForm->handleRequest($request);


        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            $slugger = new Slugify();

            $slug = $slugger->slugify($newQuestion->getTitle());
            $newQuestion->setSlug($slug);

            $newQuestion->setUser($this->getUser());
            $newQuestion->setRank(150);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newQuestion);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Enregistrement effectué'
            );
            
            return $this->redirectToRoute('home');
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $questionForm->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
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
    public function edit(Request $request, Question $question): Response
    {

        $questionForm = $this->createForm(QuestionType::class, $question);
        $questionForm->handleRequest($request);

        if ($questionForm->isSubmitted() && $questionForm->isValid()) {

            $slugger = new Slugify();
            $slug = $slugger->slugify($question->getTitle());
            $question->setSlug($slug);

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('show', ['slug' => $question->getSlug()]);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'questionForm' => $questionForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/desactiver", name="deactivate", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function deactivate(Question $question): Response
    {
        $question->setIsActive(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($question);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/{id}/activer", name="activate", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function activate(Question $question): Response
    {
        $question->setIsActive(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($question);
        $entityManager->flush();

        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/{slug}", name="show")
     */
    public function show(Question $question, Request $request)
    {
        $newAnswer = new Answer();
        $answerForm = $this->createForm(AnswerType::class, $newAnswer);
        $answerForm->handleRequest($request);

        if ($answerForm->isSubmitted() && $answerForm->isValid()) {

            $newAnswer->setQuestion($question);
            $newAnswer->setRank(25498);
            $newAnswer->setUser($this->getUser()); // l'utilisateur courant

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newAnswer);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Enregistrement effectué'
            );
            
            return $this->redirectToRoute('question_show', ['slug' => $question->getSlug()]);
        }

        return $this->render('faq/show.html.twig', [
            'question' => $question,
            'answerForm' => $answerForm->createView()
        ]);
    }
}
