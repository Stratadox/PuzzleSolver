<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use Throwable;

/**
 * Puzzle Creation Failure
 *
 * A type of exception that is thrown when the puzzle could not be created, for
 * example if the input data is invalid.
 *
 * @author Stratadox
 */
interface PuzzleCreationFailure extends Throwable
{
}
