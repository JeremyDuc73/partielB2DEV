<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ProfileController extends AbstractController
{
    #[Route('/profiles', methods: ['GET'])]
    public function getAllProfiles(ProfileRepository $repo): Response
    {
        $profiles = $repo->findAll();
        return $this->json($profiles, 200, [], ["groups"=>"profile:readAll"]);
    }

    #[Route('/profile/{id}/edit', methods: ['POST'])]
    public function editProfile(Profile $profile,ProfileRepository $repository, EntityManagerInterface $manager, SerializerInterface $serializer, Request $request)
    {
        if ($this->getUser()->getProfile() !== $profile){
            return $this->json("you can t edit this profile, it is not yours", 401);
        }else{
            $newProfile = $serializer->deserialize($request->getContent(), Profile::class, 'json');
            $profile->setDisplayName($newProfile->getDisplayName());
            $manager->persist($profile);
            $manager->flush();
            return $this->json($profile, 201, [], ["groups"=>"profile:readOne"]);
        }
    }
}
