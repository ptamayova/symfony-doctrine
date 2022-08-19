<?php

namespace App\Factory;

use App\Entity\QuestionTag;
use App\Repository\QuestionTagRepository;
use DateTimeImmutable;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<QuestionTag>
 *
 * @method static QuestionTag|Proxy createOne(array $attributes = [])
 * @method static QuestionTag[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static QuestionTag|Proxy find(object|array|mixed $criteria)
 * @method static QuestionTag|Proxy findOrCreate(array $attributes)
 * @method static QuestionTag|Proxy first(string $sortedField = 'id')
 * @method static QuestionTag|Proxy last(string $sortedField = 'id')
 * @method static QuestionTag|Proxy random(array $attributes = [])
 * @method static QuestionTag|Proxy randomOrCreate(array $attributes = [])
 * @method static QuestionTag[]|Proxy[] all()
 * @method static QuestionTag[]|Proxy[] findBy(array $attributes)
 * @method static QuestionTag[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static QuestionTag[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static QuestionTagRepository|RepositoryProxy repository()
 * @method QuestionTag|Proxy create(array|callable $attributes = [])
 */
final class QuestionTagFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'question' => QuestionFactory::new(),
            'tag' => TagFactory::new(),
            'taggedAt' => DateTimeImmutable::createFromMutable(
                self::faker()->datetime()
            ),
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(QuestionTag $questionTag): void {})
        ;
    }

    protected static function getClass(): string
    {
        return QuestionTag::class;
    }
}
