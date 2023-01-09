<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
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

        $year = $request->get('year_search','');

        $city = $request->get('city_search','');

        $paginator = $conferenceRepository->getPaginator($offset, $year, $city);
        $years = $conferenceRepository->getListYear();
        $cities = $conferenceRepository->getListCities();

        return $this->render('conference/index.html.twig', [
            'conferences' => $paginator,
            'previous' => $offset - ConferenceRepository::CONF_PER_PAGE,
            'next' => min(count($paginator), $offset + ConferenceRepository::CONF_PER_PAGE),
            'years' => $years,
            'year_search' => $year,
            'cities' => $cities,
            'city_search' => $city
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
}
