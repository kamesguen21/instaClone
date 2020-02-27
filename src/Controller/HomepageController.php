<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public $collection = null;

    /**
     * @Route("/homepage/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        /** @var User $appUser */
        /** @var User $follow */
        /** @var  Photos $post */

        $entityManager = $this->getDoctrine()->getManager();
        $page = $request->request->get('page');
        if ($page) {
            if (!$this->collection) {
                $appUser = $entityManager->getReference('App\Entity\User', $this->getUser()->getId());
                $this->collection = [];
                $followings = $appUser->getFollowings();
                foreach ($followings as $follow) {
                    foreach ($follow->getUserPhotos() as $key => $post) {
                        $this->collection[$key]['user_id'] = $follow->getId();
                        $followers[$key]['user_pic'] = $follow->getProfilePicture() ? $follow->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
                        $this->collection[$key]['id'] = $post->getId();
                        $this->collection[$key]['url'] = '/uploads/images/posts/' . $post->getImagePath();
                        $this->collection[$key]['likes'] = $post->getLikes()->count();
                        $this->collection[$key]['comments'] = $this->getComments($post->getComments());
                        $this->collection[$key]['user_id'] = $post->getId();
                    }
                }
            }
        }


        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }

    public function getComments($comments)
    {
        $jsonResponse = [];
        foreach ($comments as $key => $comment) {
            /** @var \App\Entity\Comment $comment */
            $jsonResponse[$key]['id'] = $comment->getId();
            $jsonResponse[$key]['text'] = $comment->getText();
            $jsonResponse[$key]['userId'] = $comment->getUserId()->getId();
            $jsonResponse[$key]['name'] = $comment->getUserId()->getUserName();
            $jsonResponse[$key]['pic'] = $comment->getUserId()->getProfilePicture() ? $comment->getUserId()->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
            $jsonResponse[$key]['date'] = $comment->getDateModified();
            if ($key === 3) {
                break;
            }
        }
        return $jsonResponse;
    }
}
