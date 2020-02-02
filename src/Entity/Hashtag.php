<?php

namespace App\Entity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HashtagRepository")
 */
class Hashtag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\photos", inversedBy="hashtags")
     */
    private $photo_id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $text;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PhotoHashtag", mappedBy="hashtag_id", cascade={"persist", "remove"})
     */
    private $hashtag;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getHashtag(): ?PhotoHashtag
    {
        return $this->hashtag;
    }

    public function setHashtag(?PhotoHashtag $hashtag): self
    {
        $this->hashtag = $hashtag;

        // set (or unset) the owning side of the relation if necessary
        $newHashtag_id = null === $hashtag ? null : $this;
        if ($hashtag->getHashtagId() !== $newHashtag_id) {
            $hashtag->setHashtagId($newHashtag_id);
        }

        return $this;
    }
}
