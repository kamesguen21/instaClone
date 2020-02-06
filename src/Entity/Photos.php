<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotosRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Photos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="user_photos")
     */
    private $user_id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $caption;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_path;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $image_size;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_updated;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Likes", mappedBy="photo_id")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotoHashtag", mappedBy="photo_id")
     */
    private $photo_hashtags;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhotoComment", mappedBy="photo_id")
     */
    private $comments;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->photo_hashtags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->date_created = new DateTime("now");
        $this->date_updated = new DateTime("now");

    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->date_updated = new DateTime("now");
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(?user $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): self
    {
        $this->image_path = $image_path;

        return $this;
    }

    public function getImageSize(): ?int
    {
        return $this->image_size;
    }

    public function setImageSize(?int $image_size): self
    {
        $this->image_size = $image_size;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->date_created;
    }

    public function setDateCreated(?\DateTimeInterface $date_created): self
    {
        $this->date_created = $date_created;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->date_updated;
    }

    public function setDateUpdated(?\DateTimeInterface $date_updated): self
    {
        $this->date_updated = $date_updated;

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
            $like->setPhotoId($this);
        }

        return $this;
    }

    public function removeLike(Likes $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getPhotoId() === $this) {
                $like->setPhotoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhotoHashtag[]
     */
    public function getPhotoHashtags(): Collection
    {
        return $this->photo_hashtags;
    }

    public function addPhotoHashtag(PhotoHashtag $photoHashtag): self
    {
        if (!$this->photo_hashtags->contains($photoHashtag)) {
            $this->photo_hashtags[] = $photoHashtag;
            $photoHashtag->setPhotoId($this);
        }

        return $this;
    }

    public function removePhotoHashtag(PhotoHashtag $photoHashtag): self
    {
        if ($this->photo_hashtags->contains($photoHashtag)) {
            $this->photo_hashtags->removeElement($photoHashtag);
            // set the owning side to null (unless already changed)
            if ($photoHashtag->getPhotoId() === $this) {
                $photoHashtag->setPhotoId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PhotoComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(PhotoComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPhotoId($this);
        }

        return $this;
    }

    public function removeComment(PhotoComment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPhotoId() === $this) {
                $comment->setPhotoId(null);
            }
        }

        return $this;
    }
}
