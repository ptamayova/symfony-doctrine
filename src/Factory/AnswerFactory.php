<?php

namespace App\Factory;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Answer>
 *
 * @method static Answer|Proxy createOne(array $attributes = [])
 * @method static Answer[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Answer|Proxy find(object|array|mixed $criteria)
 * @method static Answer|Proxy findOrCreate(array $attributes)
 * @method static Answer|Proxy first(string $sortedField = 'id')
 * @method static Answer|Proxy last(string $sortedField = 'id')
 * @method static Answer|Proxy random(array $attributes = [])
 * @method static Answer|Proxy randomOrCreate(array $attributes = [])
 * @method static Answer[]|Proxy[] all()
 * @method static Answer[]|Proxy[] findBy(array $attributes)
 * @method static Answer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Answer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnswerRepository|RepositoryProxy repository()
 * @method Answer|Proxy create(array|callable $attributes = [])
 */
final class AnswerFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'content' => self::faker()->text(),
            'username' => self::faker()->userName(),
            'createdAt' => self::faker()->dateTimeBetween('-1 year'),
            'votes' => self::faker()->numberBetween(-20, 50),
            'question' => QuestionFactory::new()->unpublished(),
            'status' => Answer::STATUS_APPROVED
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Answer $answer): void {})
        ;
    }

    public function needsApproval(): self
    {
        return $this->addState(['status' => Answer::STATUS_NEEDS_APPROVAL]);
    }

    protected static function getClass(): string
    {
        return Answer::class;
    }
}
