<?php

namespace App\Entity;

use App\Repository\RubricsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RubricsRepository::class)
 */
class Rubrics
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $publishDate;

    /**
     * @ORM\ManyToMany(targetEntity=News::class, mappedBy="sections")
     */
    private $new;

    public function __construct()
    {
        $this->new = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publishDate;
    }

    public function setPublishDate(\DateTimeInterface $publishDate): self
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name.' '.$this->publishDate;
    }

    /**
     * @return Collection|News[]
     */
    public function getNew(): Collection
    {
        return $this->new;
    }

    public function addNew(News $new): self
    {
        if (!$this->new->contains($new)) {
            $this->new[] = $new;
            $new->addSection($this);
        }

        return $this;
    }

    public function removeNew(News $new): self
    {
        if ($this->new->removeElement($new)) {
            $new->removeSection($this);
        }

        return $this;
    }
}
