<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Puzzle Factory
 *
 * Implement this interface to provide a unified way to set up your puzzle.
 *
 * @author Stratadox
 */
interface PuzzleFactory
{
    public function fromString(string $puzzle): Puzzle;
}
