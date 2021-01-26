<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Heuristic
 *
 * A heuristic is an approach to problem solving that employs a practical method
 * that is not guaranteed to be perfect or rational, but is sufficient for
 * reaching a short-term goal or approximation.
 *
 * @author Stratadox
 */
interface Heuristic
{
    public function estimate(Puzzle $puzzle): float;
}
