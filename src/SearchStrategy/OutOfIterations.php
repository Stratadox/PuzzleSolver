<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use RuntimeException;
use Stratadox\PuzzleSolver\NoSolution;

final class OutOfIterations extends RuntimeException implements NoSolution
{
    public static function exceeded(int $iterations): self
    {
        return new self(
            "Out of iterations: The limit of $iterations was exceeded."
        );
    }
}
