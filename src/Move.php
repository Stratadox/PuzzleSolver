<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Move
 *
 * Represents one of the moves that can be made to solve a puzzle.
 * What do you know, this might be the winning one!
 *
 * (Note: I would have used generics here, but that's not a thing in php)
 *
 * @author Stratadox
 */
interface Move
{
    public function __toString(): string;
}
