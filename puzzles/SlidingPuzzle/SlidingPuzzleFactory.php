<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingPuzzle;

use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;
use function explode;
use function preg_split;
use function str_replace;

final class SlidingPuzzleFactory implements PuzzleFactory
{
    /** @var string */
    private $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public static function make(): PuzzleFactory
    {
        return new self(',');
    }

    public function fromString(string $puzzle): Puzzle
    {
        $pieces = [];
        $puzzle = str_replace(' ', '', $puzzle);
        foreach (preg_split("/(\n)|(\r)/", $puzzle) as $line) {
            if (empty($line)) {
                continue;
            }
            $row = [];
            foreach (explode($this->separator, $line) as $value) {
                $row[] = (int) $value;
            }
            $pieces[] = $row;
        }
        return SlidingPuzzle::withPieces(...$pieces);
    }
}
