<?php

namespace App\Controller\Backend;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/backend/tag", name="backend_tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/tous-les-tags", name="show")
     */
    public function show(TagRepository $tagRepository)
    {
        $tags = $tagRepository->findAll();

        return $this->render('backend/tag/show.html.twig', [
            'tags' => $tags
        ]);
    }

    /**
     * @Route("/ajouter-un-tag", name="new")
     */
    public function newTag(Request $request)
    {
        $newTag = new Tag();
        $tagForm = $this->createForm(TagType::class, $newTag);
        $tagForm->handleRequest($request);


        if ($tagForm->isSubmitted() && $tagForm->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newTag);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Enregistrement effectué'
            );
            
            return $this->redirectToRoute('backend_tag_show');
        }

        return $this->render('/backend/tag/new.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Tag $tag)
    {

        $tagForm = $this->createForm(TagType::class, $tag);
        $tagForm->handleRequest($request);

        if ($tagForm->isSubmitted() && $tagForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'info',
                'Mise à jour effectuée'
            );

            return $this->redirectToRoute('backend_tag_show');
        }

        return $this->render('backend/tag/edit.html.twig', [
            'tag' => $tag,
            'tagForm' => $tagForm->createView(),
        ]);
    }
}
