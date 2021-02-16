<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
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
    private $article_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $article_title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $article_content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $article_image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $article_subTitle;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleName(): ?string
    {
        return $this->article_name;
    }

    public function setArticleName(string $article_name): self
    {
        $this->article_name = $article_name;

        return $this;
    }

    public function getArticleTitle(): ?string
    {
        return $this->article_title;
    }

    public function setArticleTitle(?string $article_title): self
    {
        $this->article_title = $article_title;

        return $this;
    }

    public function getArticleContent(): ?string
    {
        return $this->article_content;
    }

    public function setArticleContent(?string $article_content): self
    {
        $this->article_content = $article_content;

        return $this;
    }

    public function getArticleImage(): ?string
    {
        return $this->article_image;
    }

    public function setArticleImage(?string $article_image): self
    {
        $this->article_image = $article_image;

        return $this;
    }

    public function getArticleSubTitle(): ?string
    {
        return $this->article_subTitle;
    }

    public function setArticleSubTitle(?string $article_subTitle): self
    {
        $this->article_subTitle = $article_subTitle;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }
  
}
