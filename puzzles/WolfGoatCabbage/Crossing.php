<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Moves;
use function array_map;
use function sprintf;

final class Crossing implements Move
{
    /** @var bool */
    private $towardsGoal;
    /** @var Purchase|null */
    private $bringAlong;

    public function __construct(bool $towardsGoal, ?Purchase $bringAlong)
    {
        $this->towardsGoal = $towardsGoal;
        $this->bringAlong = $bringAlong;
    }

    public static function allCrossingsFor(Riverbank $riverbank): Moves
    {
        return new Moves(
            new Crossing($riverbank->isStart(), null),
            ...array_map(static function (Purchase $purchase) use ($riverbank) {
                return new Crossing($riverbank->isStart(), $purchase);
            }, $riverbank->purchases())
        );
    }

    public function bringAlong(): ?Purchase
    {
        return $this->bringAlong;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s with %s',
            $this->towardsGoal ? 'forwards' : 'backwards',
            $this->bringAlong ?: 'nothing'
        );
    }
}
