<?php

namespace App\Entity;

use App\Entity\Comment;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="user_id")
     */
    private $comments;
    /**
     * @ORM\Column(type="array")
     */
    private $roles;
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true,unique=true)
     */
    private $api_token;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Photos", cascade={"persist", "remove"})
     */
    private $profile_picture;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photos")
     */
    private $saved_Posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Follow", mappedBy="follower", orphanRemoval=true)
     */
    private $followers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Follow", mappedBy="following", orphanRemoval=true)
     */
    private $Followings;


    public function __construct()
    {
        $this->user_photos = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->roles = array('ROLE_USER');
        $this->saved_Posts = new ArrayCollection();
        $this->followers = new ArrayCollection();
        $this->Followings = new ArrayCollection();

    }

    public function __toString()
    {
        return $this->getUserName() . $this->getPasswordHash() . $this->getPassword() . $this->getEmail();
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
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

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string|null The encoded password if any
     */
    public function getPassword()
    {
        return $this->getPasswordHash();
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return $this->getPlainPassword();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->setPlainPassword(null);
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getApiToken(): ?string
    {
        return $this->api_token;
    }

    public function setApiToken(?string $api_token): self
    {
        $this->api_token = $api_token;

        return $this;
    }

    public function getProfilePicture(): ?Photos
    {
        return $this->profile_picture;
    }

    public function setProfilePicture(?Photos $profile_picture): self
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    /**
     * @return Collection|Photos[]
     */
    public function getSavedPosts(): Collection
    {
        return $this->saved_Posts;
    }

    public function addSavedPost(Photos $savedPost): self
    {
        if (!$this->saved_Posts->contains($savedPost)) {
            $this->saved_Posts[] = $savedPost;
        }

        return $this;
    }

    public function removeSavedPost(Photos $savedPost): self
    {
        if ($this->saved_Posts->contains($savedPost)) {
            $this->saved_Posts->removeElement($savedPost);
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(Follow $follower): self
    {
        if (!$this->followers->contains($follower)) {
            $this->followers[] = $follower;
            $follower->setFollower($this);
        }

        return $this;
    }

    public function removeFollower(Follow $follower): self
    {
        if ($this->followers->contains($follower)) {
            $this->followers->removeElement($follower);
            // set the owning side to null (unless already changed)
            if ($follower->getFollower() === $this) {
                $follower->setFollower(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Follow[]
     */
    public function getFollowings(): Collection
    {
        return $this->Followings;
    }

    public function addFollowing(Follow $following): self
    {
        if (!$this->Followings->contains($following)) {
            $this->Followings[] = $following;
            $following->setFollowing($this);
        }

        return $this;
    }

    public function removeFollowing(Follow $following): self
    {
        if ($this->Followings->contains($following)) {
            $this->Followings->removeElement($following);
            // set the owning side to null (unless already changed)
            if ($following->getFollowing() === $this) {
                $following->setFollowing(null);
            }
        }

        return $this;
    }
}
