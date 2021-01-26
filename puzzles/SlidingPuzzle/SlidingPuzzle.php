<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

final class SlidingPuzzle implements Puzzle
{
    /** @var Board */
    private $board;
    /** @var Moves */
    private $moves;

    private function __construct(Board $board, Moves $moves)
    {
        $this->board = $board;
        $this->moves = $moves;
    }

    public static function withPieces(array ...$pieces): Puzzle
    {
        return new self(new Board(...$pieces), Moves::none());
    }

    public function goalState(): string
    {
        return $this->board->goalState();
    }

    public function representation(): string
    {
        return (string) $this->board;
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        return new self(
            $this->board->afterSliding(...$moves),
            $this->moves->add(...$moves)
        );
    }

    public function isSolved(): bool
    {
        return $this->board->isSolved();
    }

    public function movesSoFar(): Moves
    {
        return $this->moves;
    }

    public function possibleMoves(): Moves
    {
        return new Moves(...$this->board->possibleSlides());
    }
}
