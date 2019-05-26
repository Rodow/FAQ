<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/tag", name="tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/{id}", name="question")
     */
    public function index(Tag $tag, TagRepository $tagRepository)
    {

        $tags = $tagRepository->findAll();

        return $this->render('tag/questionsByTag.html.twig', [
            'tag' => $tag,
            'tags' => $tags
        ]);
    }

}
