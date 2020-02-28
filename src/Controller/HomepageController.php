<?php

namespace App\Controller;

use App\Entity\Photos;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    public $collection = null;
    const PAGINATOR = 2;

    /**
     * @Route("/homepage/{page}", name="homepage", requirements={"page"="\d+"})
     * @param int $page
     * @return Response
     */
    public function index(int $page = 0)
    {
        /** @var User $appUser */
        /** @var User $follow */
        /** @var  Photos $post */
        if ($page != 0) {
            $page--;
        }
        $entityManager = $this->getDoctrine()->getManager();
        if (!$this->collection) {
            $appUser = $entityManager->getReference('App\Entity\User', $this->getUser()->getId());
            $this->collection = [];
            $followings = $appUser->getFollowings();
            $iteration = 1;
            foreach ($followings as $follow) {
                foreach ($follow->getUserPhotos() as $key => $post) {
                    $pic['user_id'] = $follow->getId();
                    $pic['user_name'] = $follow->getUserName();
                    $pic['user_pic'] = $follow->getProfilePicture() ? $follow->getProfilePicture()->getSrc() : '/build/user-avatar.svg';
                    $pic['id'] = $post->getId();
                    $pic['url'] = '/uploads/images/posts/' . $post->getImagePath();
                    $pic['likes'] = $post->getLikes()->count();
                    $pic['comments'] = $this->getComments($post->getComments());
                    $pic['caption'] = $post->getCaption();
                    $pic['tags'] = $post->getHashtagsAsString();
                    $pic['date'] = $this->time_elapsed_string($post->getDateUpdated()->format('c'));
                    $like = $this->liked($appUser, $post);
                    $pic['like'] = $like ? $like : null;
                    array_push($this->collection, $pic);
                }
                $iteration++;
            }
            usort($this->collection, array('App\Controller\HomepageController', 'cmp'));
        }
        $postPage = array_slice($this->collection, $page * self::PAGINATOR, self::PAGINATOR);

        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
            'data' => $postPage,
            'user' => $appUser,
            'pages' => ($page * self::PAGINATOR) > count($this->collection) ? 1 : 0,
            'page' => $page
        ]);
    }

    public function liked($user, $photo)
    {
        /*ToDo: need optimisation*/
        /** @var  Photos $photo */
        /** @var User $user */
        $likes = $user->getLikes();
        foreach ($likes as $like) {
            if ($like->getPhotoId()->getId() === $photo->getId()) {
                return $like->getId();
            }
        }
        return null;
    }

    function time_elapsed_string($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    static function cmp($a, $b)
    {
        if ($a['date'] == $b['date']) {
            return 0;
        }
        return ($a['date'] > $b['date']) ? -1 : 1;
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
            $jsonResponse[$key]['date'] = $this->time_elapsed_string($comment->getDateModified()->format('c'));
            if ($key === 3) {
                break;
            }
        }
        return $jsonResponse;
    }
}
