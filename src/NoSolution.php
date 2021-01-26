<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use Throwable;

/**
 * No Solution
 *
 * A type of exception that is thrown when no solution could be found, for
 * whatever reason.
 *
 * @author Stratadox
 */
interface NoSolution extends Throwable
{
}
