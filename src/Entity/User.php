<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password_hash;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $profil_pic;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_modified;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photos", mappedBy="user_id")
     */
    private $user_photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="user_id")
     */
    private $likes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SavedPhotos", inversedBy="user_id")
     */
    private $saved_photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Following", mappedBy="user_id")
     */
    private $followings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Follower", mappedBy="user_id")
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user_id")
     */
    private $comments;
    public function __construct()
    {
        $this->user_photos = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->followings = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    public function setUserName(?string $user_name): self
    {
        $this->user_name = $user_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(?string $password_hash): self
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getProfilPic(): ?string
    {
        return $this->profil_pic;
    }

    public function setProfilPic(?string $profil_pic): self
    {
        $this->profil_pic = $profil_pic;

        return $this;
    }

    public function getDateCreated(): ?DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateModified(): ?DateTimeInterface
    {
        return $this->date_modified;
    }

    public function setDateModified(?DateTimeInterface $date_modified): self
    {
        $this->date_modified = $date_modified;

        return $this;
    }
    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->date_created = new \DateTime("now");
        $this->date_modified = new \DateTime("now");

    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_modified = new \DateTime("now");
    }

    /**
     * @return Collection|Photos[]
     */
    public function getUserPhotos(): Collection
    {
        return $this->user_photos;
    }

    public function addUserPhoto(Photos $userPhoto): self
    {
        if (!$this->user_photos->contains($userPhoto)) {
            $this->user_photos[] = $userPhoto;
            $userPhoto->setUserId($this);
        }

        return $this;
    }

    public function removeUserPhoto(Photos $userPhoto): self
    {
        if ($this->user_photos->contains($userPhoto)) {
            $this->user_photos->removeElement($userPhoto);
            // set the owning side to null (unless already changed)
            if ($userPhoto->getUserId() === $this) {
                $userPhoto->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Likes[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Likes $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUserId($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getUserId() === $this) {
                $like->setUserId(null);
            }
        }

        return $this;
    }

    public function getSavedPhotos(): ?SavedPhotos
    {
        return $this->saved_photos;
    }

    public function setSavedPhotos(?SavedPhotos $saved_photos): self
    {
        $this->saved_photos = $saved_photos;

        return $this;
    }

    /**
     * @return Collection|Following[]
     */
    public function getFollowings(): Collection
    {
        return $this->followings;
    }

    public function addFollowing(Following $following): self
    {
        if (!$this->followings->contains($following)) {
            $this->followings[] = $following;
            $following->setUserId($this);
        }

        return $this;
    }

    public function removeFollowing(Following $following): self
    {
        if ($this->followings->contains($following)) {
            $this->followings->removeElement($following);
            // set the owning side to null (unless already changed)
            if ($following->getUserId() === $this) {
                $following->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follower[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follower $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->setUserId($this);
        }

        return $this;
    }

    public function removeFollower(Follower $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            // set the owning side to null (unless already changed)
            if ($follower->getUserId() === $this) {
                $follower->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUserId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUserId() === $this) {
                $comment->setUserId(null);
            }
        }

        return $this;
    }
}