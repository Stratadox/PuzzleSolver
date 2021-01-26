<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NetworkNavigation;

use Stratadox\PuzzleSolver\WeightedMove;

final class Jump implements WeightedMove
{
    /** @var string */
    private $target;
    /** @var float */
    private $cost;

    private function __construct(string $target, float $cost)
    {
        $this->target = $target;
        $this->cost = $cost;
    }

    public static function to(string $target, float $cost): WeightedMove
    {
        return new self($target, $cost);
    }

    public function __toString(): string
    {
        return 'Go to ' . $this->target;
    }

    public function target(): string
    {
        return $this->target;
    }

    public function cost(): float
    {
        return $this->cost;
    }
}
