<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NQueens;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;
use function explode;
use function implode;

final class NQueensPuzzle implements Puzzle
{
    /** @var Board */
    private $board;
    /** @var Moves */
    private $moves;

    public function __construct(Board $board, Moves $moves)
    {
        $this->board = $board;
        $this->moves = $moves;
    }

    public static function forQueens(int $n): Puzzle
    {
        return new self(Board::ofSize($n), Moves::none());
    }

    public function representation(): string
    {
        $board = explode("\n", (string) $this->board);
        /** @var QueenPlacement $queen */
        foreach ($this->moves as $queen) {
            $board[1 + $queen->row()][2 + $queen->column() * 3] = "Q";
        }
        return implode("\n", $board);
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        return new self($this->board, $this->moves->add(...$moves));
    }

    public function isSolved(): bool
    {
        return $this->board->isSolvedWith(...$this->moves);
    }

    public function movesSoFar(): Moves
    {
        return $this->moves;
    }

    public function possibleMoves(): Moves
    {
        return $this->board->possibleMovesAfter(...$this->moves);
    }
}
