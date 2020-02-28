<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\Hashtag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/actions/search", name="search")
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $term = $request->request->get('term');
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepository->findByName($term);
        $data = [];
        foreach ($users as $key => $user) {
            /** @var \App\Entity\User $user */
            $data[$key]['id'] = $user->getId();
            $data[$key]['name'] = $user->getUserName();
            $data[$key]['pic'] = $user->getProfilePicture() ? $user->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
        }
        return $this->json(['success' => true, 'users' => $data]);

    }
}