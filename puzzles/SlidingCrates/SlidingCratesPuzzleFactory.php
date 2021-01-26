<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use Stratadox\PuzzleSolver\InvalidFormat;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;
use function explode;
use function substr_count;
use function trim;

final class SlidingCratesPuzzleFactory implements PuzzleFactory
{
    /** @var string */
    private $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public static function make(): self
    {
        return new self('TARGET:');
    }

    public function fromString(string $puzzle): Puzzle
    {
        if (substr_count($puzzle, $this->separator) !== 1) {
            throw InvalidFormat::couldNotLoad('sliding crates', $puzzle, "Missing `$this->separator` in input.");
        }
        [$level, $target] = explode($this->separator, $puzzle);
        return SlidingCratesPuzzle::fromString($level, trim($target)[0]);
    }
}
