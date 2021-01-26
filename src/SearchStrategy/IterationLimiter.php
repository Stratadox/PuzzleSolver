<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;

/**
 * Iteration Limiter
 *
 * Iteration Limiter aborts the search when the amount of considered candidates
 * exceeds a set limit.
 *
 * @author Stratadox
 */
final class IterationLimiter implements SearchStrategy
{
    /** @var SearchStrategy */
    private $strategy;
    /** @var int */
    private $limit;
    /** @var int */
    private $current = 0;

    private function __construct(SearchStrategy $strategy, int $limit)
    {
        $this->strategy = $strategy;
        $this->limit = $limit;
    }

    public static function limitingTo(int $limit, SearchStrategy $strategy): self
    {
        return new self($strategy, $limit);
    }

    public function isOngoing(): bool
    {
        return $this->strategy->isOngoing();
    }

    public function consider(Puzzle $puzzle): bool
    {
        if (!$this->strategy->consider($puzzle)) {
            return false;
        }
        if (++$this->current > $this->limit) {
            throw OutOfIterations::exceeded($this->limit);
        }
        return true;
    }

    public function nextCandidate(): Puzzle
    {
        return $this->strategy->nextCandidate();
    }
}
