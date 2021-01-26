<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use function assert;
use function in_array;

final class Purchase
{
    private const WOLF = 'wolf';
    private const GOAT = 'goat';
    private const CABBAGE = 'cabbage';
    private const ALLOWED = [self::WOLF, self::GOAT, self::CABBAGE];
    private const EATS = [
        self::WOLF => self::GOAT,
        self::GOAT => self::CABBAGE,
        self::CABBAGE => null,
    ];

    /** @var string */
    private $type;

    private function __construct(string $type)
    {
        assert(in_array($type, self::ALLOWED));
        $this->type = $type;
    }

    public static function wolf(): self
    {
        return new self(self::WOLF);
    }

    public static function goat(): self
    {
        return new self(self::GOAT);
    }

    public static function cabbage(): self
    {
        return new self(self::CABBAGE);
    }

    public static function fromString(string $purchase): self
    {
        return new self($purchase);
    }

    public function eats(Purchase $other): bool
    {
        return self::EATS[$this->type] === $other->type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
