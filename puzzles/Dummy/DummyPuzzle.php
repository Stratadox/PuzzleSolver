<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Dummy;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

final class DummyPuzzle implements Puzzle
{
    public function representation(): string
    {
        return 'Dummy';
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        return $this;
    }

    public function isSolved(): bool
    {
        return true;
    }

    public function movesSoFar(): Moves
    {
        return Moves::none();
    }

    public function possibleMoves(): Moves
    {
        return Moves::none();
    }
}
