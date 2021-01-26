<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Factory for @see PuzzleSolver
 *
 * @author Stratadox
 */
interface SolverFactory
{
    public function forAPuzzleWith(
        PuzzleDescription $puzzle,
        SearchSettings $settings
    ): PuzzleSolver;
}
