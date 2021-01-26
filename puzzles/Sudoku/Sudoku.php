<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\Sudoku;

use Stratadox\PuzzleSolver\Moves;
use function assert;

final class Sudoku
{
    /** @var int[][]|null[][] */
    private $numbers;
    /** @var bool[][] */
    private $rows = [];
    /** @var bool[][] */
    private $cols = [];
    /** @var bool[][][] */
    private $boxes = [];
    /** @var int[]|null */
    private $firstEmptyPosition;

    public function __construct(array ...$numbers)
    {
        $this->numbers = $numbers;
        foreach ($numbers as $r => $row) {
            foreach ($row as $c => $number) {
                if (null !== $number) {
                    $this->rows[$r][$number] = true;
                    $this->cols[$c][$number] = true;
                    $this->boxes[$r / 3][$c / 3][$number] = true;
                } elseif (null === $this->firstEmptyPosition) {
                    $this->firstEmptyPosition = [$r, $c];
                }
            }
        }
    }

    public function isFull(): bool
    {
        return null === $this->firstEmptyPosition;
    }

    public function possibilitiesForNextEmptyPosition(): Moves
    {
        if (null === $this->firstEmptyPosition) {
            return Moves::none();
        }
        [$r, $c] = $this->firstEmptyPosition;
        return Entry::allPossibilitiesFor($r, $c)->filterWith(function (Entry $entry) {
            return $this->allows($entry);
        });
    }

    // For immutable sudoku puzzle implementations
    public function withFilledIn(Entry ...$entries): self
    {
        $numbers = $this->numbers;
        foreach ($entries as $entry) {
            assert($this->allows($entry));
            $numbers[$entry->row()][$entry->column()] = $entry->number();
        }
        return new self(...$numbers);
    }

    public function __toString(): string
    {
        $sudoku = "\n+-------+-------+-------+ \n";
        foreach ($this->numbers as $r => $row) {
            $sudoku .= '| ';
            foreach ($row as $c => $value) {
                $sudoku .= ($value ?: ' ') . ' ';
                if ($c % 3 === 2) {
                    $sudoku .= '| ';
                }
            }
            $sudoku .= "\n";
            if ($r % 3 === 2) {
                $sudoku .= "+-------+-------+-------+ \n";
            }
        }
        return $sudoku;
    }

    private function allows(Entry $entry): bool
    {
        return null === $this->numbers[$entry->row()][$entry->column()]
            && $this->canAddInRow($entry->row(), $entry->number())
            && $this->canAddInColumn($entry->column(), $entry->number())
            && $this->canAddInRegion($entry->row(), $entry->column(), $entry->number());
    }

    private function canAddInRow(int $row, int $number): bool
    {
        return !isset($this->rows[$row][$number]);
    }

    private function canAddInColumn(int $column, int $number): bool
    {
        return !isset($this->cols[$column][$number]);
    }

    private function canAddInRegion(int $row, int $column, int $number): bool
    {
        return !isset($this->boxes[$row / 3][$column / 3][$number]);
    }
}
