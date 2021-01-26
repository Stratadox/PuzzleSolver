<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Solution Renderer
 *
 * Once the solution has been found, a solution renderer can render it to a
 * file or another destination.
 *
 * @author Stratadox
 */
interface SolutionRenderer
{
    public function render(Solution $solution): void;
}
