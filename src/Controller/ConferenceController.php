<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{
    #[Route('/', name: 'app_conference')]
    public function index(ConferenceRepository $conferenceRepository, Request $request): Response
    {   
        $offset = max(0, $request->get('offset',0));

        $year_search = $request->get('year_search','');

        $city_search = $request->get('city_search','');

        $paginator = $conferenceRepository->getPaginator($offset, $year_search, $city_search);
        $years = $conferenceRepository->getListYear();
        $cities = $conferenceRepository->getListCities();

        return $this->render('conference/index.html.twig', [
            'conferences' => $paginator,
            'previous' => $offset - ConferenceRepository::CONF_PER_PAGE,
            'next' => min(count($paginator), $offset + ConferenceRepository::CONF_PER_PAGE),
            'years' => $years,
            'year_search' => $year_search,
            'cities' => $cities,
            'city_search' => $city_search
        ]);
    }

    // #[Route('/conference/{id}', name: 'ficheConference')]
    // public function show(string $id, ConferenceRepository $conferenceRepository): Response
    // {   
    //     $conference = $conferenceRepository->find($id);
    //     return $this->render('conference/show.html.twig', [
    //         'conference' => $conference
    //     ]);
    // }
    
    // Peut aussi s'écrire comme ci-dessous, car l'id est un champs qui est présent sur toutes les tables

    #[Route('/conference/{id}', name: 'ficheConference')]
    public function show(Conference $conference, CommentRepository $commentRepo, Request $request): Response
    {   
        $offset = max(0, $request->get('offset',0));
        $paginator = $commentRepo->getPaginatorByConference($conference, $offset);

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::COMMENTS_PER_PAGE)
        ]);
    }

    #[Route('/conference/{id}/newComment', name: 'ficheConference_newComment')]
    public function newComment(Conference $conference, Request $request, CommentRepository $commentRepo): Response
    {   
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        $message = '';
        if($form->isSubmitted()){
            if($form->isValid()){
                $comment->setConference($conference);
                $comment->setCreatedAt(new \DateTimeImmutable());
                $commentRepo->save($comment, true);
                return $this->redirectToRoute('ficheConference', ['id' => $conference->getId()]);
            }
            else{
                $message = 'La saisi n\'est pas valide';
            }
        }
        return $this->render('conference/new_comment.html.twig', [
            'conference' => $conference,
            'form_comment' => $form->createView(),
            'message' => $message,
        ]);
    }
}
