<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Sudoku;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;

final class SudokuPuzzle implements Puzzle
{
    /** @var Sudoku */
    private $sudoku;
    /** @var Moves */
    private $entries;

    private function __construct(Sudoku $sudoku, Moves $entries)
    {
        $this->sudoku = $sudoku;
        $this->entries = $entries;
    }

    public static function fromArrays(array ...$arrays): Puzzle
    {
        return new self(new Sudoku(...$arrays), Moves::none());
    }

    public function isSolved(): bool
    {
        return $this->sudoku->isFull();
    }

    public function movesSoFar(): Moves
    {
        return $this->entries;
    }

    public function representation(): string
    {
        return (string) $this->sudoku;
    }

    public function possibleMoves(): Moves
    {
        return $this->sudoku->possibilitiesForNextEmptyPosition();
    }

    public function afterMaking(Move ...$entries): Puzzle
    {
        $new = clone $this;
        $new->sudoku = $new->sudoku->withFilledIn(...$entries);
        $new->entries = $new->entries->add(...$entries);
        return $new;
    }
}
