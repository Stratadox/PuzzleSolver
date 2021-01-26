<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;
use function assert;

final class RiverCrossingPuzzle implements Puzzle
{
    /** @var Farmer */
    private $farmer;
    /** @var Moves */
    private $crossings;

    private function __construct(Farmer $farmer, Moves $crossings)
    {
        $this->farmer = $farmer;
        $this->crossings = $crossings;
    }

    public static function begin(): Puzzle
    {
        return new self(Farmer::withWolfGoatAndCabbage(), Moves::none());
    }

    public function isSolved(): bool
    {
        return $this->farmer->hasMovedAllPurchasesAlong();
    }

    public function movesSoFar(): Moves
    {
        return $this->crossings;
    }

    public function representation(): string
    {
        return (string) $this->farmer;
    }

    public function possibleMoves(): Moves
    {
        return $this->farmer->possibleCrossings();
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        $new = clone $this;
        foreach ($moves as $move) {
            assert($move instanceof Crossing);
            $new->farmer = $new->farmer->after($move);
        }
        $new->crossings = $new->crossings->add(...$moves);
        return $new;
    }
}
