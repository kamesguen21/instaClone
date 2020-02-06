<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\SavedPhotosRepository")
 * @ORM\HasLifecycleCallbacks
 */
class SavedPhotos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\photos", cascade={"persist", "remove"})
     */
    private $photo_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\user", mappedBy="saved_photos")
     */
    private $user_id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_updated;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
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

    public function getPhotoId(): ?photos
    {
        return $this->photo_id;
    }

    public function setPhotoId(?photos $photo_id): self
    {
        $this->photo_id = $photo_id;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(user $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
            $userId->setSavedPhotos($this);
        }

        return $this;
    }

    public function removeUserId(user $userId): self
    {
        if ($this->user_id->contains($userId)) {
            $this->user_id->removeElement($userId);
            // set the owning side to null (unless already changed)
            if ($userId->getSavedPhotos() === $this) {
                $userId->setSavedPhotos(null);
            }
        }

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
}
