<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
{
    use TimestampableEntity;

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
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"name"}, style="lower")
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $question;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $askedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $votes = 0;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $answers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=QuestionTag::class, mappedBy="question")
     */
    private $questionTags;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->questionTags = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    /**
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAskedAt(): ?\DateTimeInterface
    {
        return $this->askedAt;
    }

    /**
     * @param \DateTimeInterface|null $askedAt
     * @return $this
     */
    public function setAskedAt(?\DateTimeInterface $askedAt): self
    {
        $this->askedAt = $askedAt;

        return $this;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @param int $votes
     * @return $this
     */
    public function setVotes(int $votes): self
    {
        $this->votes = $votes;

        return $this;
    }

    /**
     * @return string
     */
    public function getVotesString(): string
    {
        $symbol = $this->votes > 0 ? '+' : '-';

        return $symbol . abs($this->votes);
    }

    /**
     * @return $this
     */
    public function upVote(): self
    {
        $this->votes++;

        return $this;
    }

    /**
     * @return $this
     */
    public function downVote(): self
    {
        $this->votes--;

        return $this;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getApprovedAnswers(): Collection
    {
        return $this->answers->matching(
            AnswerRepository::createApprovedCriteria()
        );
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getQuestionText(): string
    {
        if (!$this->getQuestion()) {
            return '';
        }

        return (string) $this->getQuestion();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, QuestionTag>
     */
    public function getQuestionTags(): Collection
    {
        return $this->questionTags;
    }

    public function addQuestionTag(QuestionTag $questionTag): self
    {
        if (!$this->questionTags->contains($questionTag)) {
            $this->questionTags[] = $questionTag;
            $questionTag->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionTag(QuestionTag $questionTag): self
    {
        if ($this->questionTags->removeElement($questionTag)) {
            // set the owning side to null (unless already changed)
            if ($questionTag->getQuestion() === $this) {
                $questionTag->setQuestion(null);
            }
        }

        return $this;
    }
}
