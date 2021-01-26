<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use RuntimeException;
use function sprintf;

/**
 * No Possible Solution
 *
 * Exception thrown when the solver could not find any solutions to the puzzle,
 * which usually means the puzzle has no solution and can be considered
 * "impossible".
 *
 * @author Stratadox
 */
final class NoPossibleSolution extends RuntimeException implements NoSolution
{
    public static function to(Puzzle $puzzle): self
    {
        return new self(sprintf(
            'No solution was found to the puzzle `%s`',
            $puzzle->representation()
        ));
    }
}
