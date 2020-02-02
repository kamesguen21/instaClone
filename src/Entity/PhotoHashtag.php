<?php

namespace App\Entity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoHashtagRepository")
 */
class PhotoHashtag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\hashtag", inversedBy="hashtag", cascade={"persist", "remove"})
     */
    private $hashtag_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\photos", inversedBy="photo_hashtags")
     */
    private $photo_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHashtagId(): ?hashtag
    {
        return $this->hashtag_id;
    }

    public function setHashtagId(?hashtag $hashtag_id): self
    {
        $this->hashtag_id = $hashtag_id;

        return $this;
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
}
