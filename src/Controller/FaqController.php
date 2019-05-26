<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FaqController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $tagRepository = $this->getDoctrine()->getRepository(Tag::class);

        $searchTitle = $request->request->get('title'); //POST

        if ($searchTitle){
            $questions = $repository->findByTitle($searchTitle);
        } else {
            $questions = $repository->findAllQuestion();
        }

        $tags = $tagRepository->findAll();

        return $this->render('faq/index.html.twig', [
            'questions' => $questions,
            'tags' => $tags,
        ]);
    }
}
