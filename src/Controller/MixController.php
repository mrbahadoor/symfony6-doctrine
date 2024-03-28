<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\VinylMix;
use App\Repository\VinylMixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MixController extends AbstractController
{
    #[Route('/mix/new', name: 'mix_new')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        $mix = new VinylMix();
        $mix->setTitle('Do you remember.... Phil Collins');
        $mix->setDescription('This is a mix of Phil Collins greatest hits');
        $genres = VinylMix::getGenres();

        $mix->setGenre($genres[array_rand($genres)]);
        $mix->setTrackCount(rand(5, 20));
        $mix->setVotes(rand(-50, 50));

        // Prepare the entity manager to save the new mix
        $entityManager->persist($mix);

        // Save the new mix to the database
        $entityManager->flush();

        return new Response(sprintf(
            'Mix %d is %d tracks of pure 80\'s heaven',
            $mix->getId(),
            $mix->getTrackCount()
        ));

    }

    #[Route('/mix/{id}', name: 'app_mix_show')]
    public function show(int $id, VinylMixRepository $mixRepository): Response
    {
        $mix = $mixRepository->find($id);
        
        return $this->render('mix/show.html.twig', [
            'mix' => $mix,
        ]);
    }

    #[Route('/mix/{id}/vote', name: 'app_mix_vote', methods: ['POST'])]
    public function vote(int $id, VinylMixRepository $mixRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $direction = $request->request->get('direction', 'up');

        $mix = $mixRepository->find($id);
        if ($direction === 'up') {
            $mix->upVote();
        } elseif ($direction === 'down') {
            $mix->downVote();
        }

        $entityManager->flush();

        $this->addFlash('success', 'Your vote was counted!');

        return $this->redirectToRoute('app_mix_show', ['id' => $mix->getId()]);
    }
}