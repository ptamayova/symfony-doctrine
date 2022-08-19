<?php

namespace App\Factory;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
/**
 * @method static Question|Proxy findOrCreate(array $attributes)
 * @method static Question|Proxy random()
 * @method static Question[]|Proxy[] randomSet(int $number)
 * @method static Question[]|Proxy[] randomRange(int $min, int $max)
 * @method static QuestionRepository|RepositoryProxy repository()
 * @method Question|Proxy create($attributes = [])
 * @method Question[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class QuestionFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
        'name' => self::faker()->realText(50),
        'question' => self::faker()->paragraphs(
            self::faker()->numberBetween(1, 4),
            true
        ),
        'username' => self::faker()->userName(),
        'askedAt' => self::faker()->dateTimeBetween('-100 days', '-1 minute'),
        'votes' => rand(-20, 50)
    ];
    }
    protected function initialize(): self
    {
        return $this
             //->afterInstantiate(function(Question $question) { })
        ;
    }

    public function unpublished(): self
    {
        return $this->addState(['askedAt' => null]);
    }

    protected static function getClass(): string
    {
        return Question::class;
    }
}
