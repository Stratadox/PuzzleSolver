<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;

final class RiverCrossingFactory implements PuzzleFactory
{
    public static function make(): PuzzleFactory
    {
        return new self();
    }

    public function fromString(string $puzzle): Puzzle
    {
        return RiverCrossingPuzzle::begin();
    }
}
