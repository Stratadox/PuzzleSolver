<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle;

use RuntimeException;
use function array_fill;
use function array_map;
use function assert;
use function count;
use function implode;
use function range;
use function str_replace;
use const PHP_EOL;

final class Board
{
    /** @var int[][] */
    private $pieces;
    /** @var int */
    private $openRow;
    /** @var int */
    private $openCol;
    /** @var int */
    private $height;
    /** @var int */
    private $width;

    public function __construct(array ...$pieces)
    {
        $this->pieces = $pieces;
        [$this->openRow, $this->openCol] = $this->findOpenSpace(...$pieces);
        $this->height = count($pieces);
        assert($this->height > 0);
        $this->width = count($pieces[0]);
    }

    public function afterSliding(Slide ...$slides): self
    {
        $pieces = $this->pieces;
        foreach ($slides as $slide) {
            $pieces = $slide->applyTo($pieces);
        }
        return new self(...$pieces);
    }

    public function isSolved(): bool
    {
        return $this->goalState() === $this->currentState();
    }

    public function currentState(): string
    {
        return implode(array_map('implode', $this->pieces));
    }

    public function goalState(): string
    {
        return implode(range(1, $this->width * $this->height - 1)) . '0';
    }

    /** @return Slide[] */
    public function possibleSlides(): array
    {
        $slides = [];
        if ($this->openRow < $this->height - 1) {
            $slides[] = Slide::up($this->pieces, $this->openRow + 1, $this->openCol);
        }
        if ($this->openRow > 0) {
            $slides[] = Slide::down($this->pieces, $this->openRow - 1, $this->openCol);
        }
        if ($this->openCol < $this->width - 1) {
            $slides[] = Slide::left($this->pieces, $this->openRow, $this->openCol + 1);
        }
        if ($this->openCol > 0) {
            $slides[] = Slide::right($this->pieces, $this->openRow, $this->openCol - 1);
        }
        return $slides;
    }

    public function __toString(): string
    {
        return str_replace('0', ' ', implode(PHP_EOL, array_map(
            'implode',
            array_fill(0, $this->width, ', '),
            $this->pieces
        ))) . '  ';
    }

    /** @return int[] */
    private function findOpenSpace(array ...$pieces): array
    {
        foreach ($pieces as $r => $row) {
            foreach ($row as $c => $value) {
                if (0 === $value) {
                    return [$r, $c];
                }
            }
        }
        throw new RuntimeException('No open space!');
    }
}
