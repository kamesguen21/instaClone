<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Follow;
use App\Entity\Follower;
use App\Entity\Following;
use App\Entity\Hashtag;
use App\Entity\Likes;
use App\Entity\Photos;
use App\Entity\SavedPhotos;
use App\Entity\User;

use App\Form\type\uploadType;
use App\Form\type\UserType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProfileController extends AbstractController
{
    private $logger;

    /**
     * ProfileController constructor.
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

    }

    /**
     * @Route("/profile/{id}", name="profile")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function index(User $user, Request $request)
    {
        $photo = new Photos();
        $form = $this->createForm(uploadType::class, $photo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo->setUserId($user);
            $entityManager = $this->getDoctrine()->getManager();
            $tags = $photo->getTagsText();
            $tags = explode(' ', $tags);
            foreach ($tags as $tag) {
                $hashTag = new Hashtag();
                $hashTag->setText($tag);
                $hashTag->setPhotoId($photo);
                $entityManager->persist($hashTag);
                $photo->addHashtag($hashTag);
            }
            $entityManager->persist($photo);
            if ($photo->getSetAsProfile()) {
                $user->setProfilePicture($photo);
                $entityManager->persist($user);
            }
            $entityManager->flush();
            $user->setProfilePicture(null);

            return $this->redirectToRoute('profile', ['id' => $user->getId()]);

        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/actions/like", name="like")
     * @param Request $request
     * @return Response
     */
    public function like(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $photoId = $request->request->get('photo_id');
        $userId = $request->request->get('user_id');
        $photo = $entityManager->getReference('App\Entity\Photos', $photoId);
        $like = new Likes();
        $like->setUserId($entityManager->getReference('App\Entity\User', $userId));
        $like->setPhotoId($photo);
        $entityManager->persist($like);
        $photo->addLike($like);
        $entityManager->persist($photo);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/unlike", name="unlike")
     * @param Request $request
     * @return Response
     */
    public function unlike(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var \App\Entity\Photos $photo */
        $photoId = $request->request->get('photo_id');
        $like = $request->request->get('id');
        $photo = $entityManager->getReference('App\Entity\Photos', $photoId);
        $like = $entityManager->getReference('App\Entity\Likes', $like);
        $photo->removeLike($like);
        $entityManager->persist($photo);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/comment", name="comment")
     * @param Request $request
     * @return Response
     */
    public function comment(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $photoId = $request->request->get('photo_id');
        $userId = $request->request->get('user_id');
        $text = $request->request->get('text');
        $comment = new Comment();
        $comment->setUserId($entityManager->getReference('App\Entity\User', $userId));
        $comment->setPost($entityManager->getReference('App\Entity\Photos', $photoId));
        $comment->setText($text);
        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/save", name="save")
     * @param Request $request
     * @return Response
     */
    public function save(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $photoId = $request->request->get('photo_id');
        $userId = $request->request->get('user_id');
        $user = $entityManager->getReference('App\Entity\User', $userId);
        $user->addSavedPost($entityManager->getReference('App\Entity\Photos', $photoId));
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/comments", name="comments")
     * @param Request $request
     * @return Response
     */
    public function getComments(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        /** @var \App\Entity\Photos $photo */
        $photoId = $request->request->get('photo_id');
        $photo = $entityManager->getReference('App\Entity\Photos', $photoId);
        $comments = $photo->getComments();
        $jsonResponse = [];
        foreach ($comments as $key => $comment) {
            /** @var \App\Entity\Comment $comment */
            $jsonResponse[$key]['id'] = $comment->getId();
            $jsonResponse[$key]['text'] = $comment->getText();
            $jsonResponse[$key]['userId'] = $comment->getUserId()->getId();
            $jsonResponse[$key]['name'] = $comment->getUserId()->getUserName();
            $jsonResponse[$key]['pic'] = $comment->getUserId()->getProfilePicture() ? $comment->getUserId()->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
            $jsonResponse[$key]['date'] = $comment->getDateModified();
        }
        return $this->json(['success' => true, 'comments' => $jsonResponse]);
    }

    /**
     * @Route("/actions/likes", name="likes")
     * @param Request $request
     * @return Response
     */
    public function getLikes(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $photoId = $request->request->get('photo_id');
        $photo = $entityManager->getReference('App\Entity\Photos', $photoId);
        $likes = $photo->getLikes();
        $jsonResponse = [];
        foreach ($likes as $key => $like) {
            /** @var \App\Entity\Likes $like */
            $jsonResponse[$key]['id'] = $like->getId();
            $jsonResponse[$key]['userId'] = $like->getUserId()->getId();
            $jsonResponse[$key]['name'] = $like->getUserId()->getUserName();
        }

        return $this->json(['success' => true, 'likes' => $jsonResponse]);
    }

    /**
     * @Route("/actions/follow", name="follow")
     * @param Request $request
     * @return Response
     */
    public function follow(Request $request)
    {
        /** @var User $appUser */
        /** @var User $user */

        $entityManager = $this->getDoctrine()->getManager();
        $appUser = $entityManager->getReference('App\Entity\User', $request->request->get('app_user'));
        $user = $entityManager->getReference('App\Entity\User', $request->request->get('user'));
        $appUser->follow($user);
        $entityManager->persist($appUser);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/unfollow", name="unfollow")
     * @param Request $request
     * @return Response
     */
    public function unfollow(Request $request)
    {
        /** @var User $appUser */
        /** @var User $user */

        $entityManager = $this->getDoctrine()->getManager();
        $appUser = $entityManager->getReference('App\Entity\User', $request->request->get('app_user'));
        $user = $entityManager->getReference('App\Entity\User', $request->request->get('user'));
        $appUser->unfollow($user);
        $entityManager->persist($appUser);
        $entityManager->flush();
        return $this->json(['success' => true]);
    }

    /**
     * @Route("/actions/follows", name="follows")
     * @param Request $request
     * @return Response
     */
    public function follows(Request $request)
    {
        /** @var User $appUser */
        /** @var User $user */

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getReference('App\Entity\User', $request->request->get('user'));
        $followers = [];
        $followings = [];
        foreach ($user->getFollowers() as $key => $follower) {
            $followers[$key]['id'] = $follower->getId();
            $followers[$key]['name'] = $follower->getUserName();
            $followers[$key]['pic'] = $follower->getProfilePicture() ? $follower->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
        }
        foreach ($user->getFollowings() as $key => $following) {
            $followings[$key]['id'] = $following->getId();
            $followings[$key]['name'] = $following->getUserName();
            $followings[$key]['pic'] = $following->getProfilePicture() ? $following->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
        }
        return $this->json(['success' => true, 'followers' => $followers, 'followings' => $followings]);
    }

}
