<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use function assert;
use function strtolower;

/**
 * Find
 *
 * Value object that provides information on the kind of goal for the puzzle.
 * Are we looking for just one solution? All of them? Etc.
 *
 * @author Stratadox
 */
final class Find
{
    private const THE_ONLY_SOLUTION = 0;
    private const A_BEST_SOLUTION = 1;
    private const ALL_BEST_SOLUTIONS = 2;
    private const ALL_LOOPLESS_SOLUTIONS = 3;
    private const MAP = [
        'the only solution' => FIND::THE_ONLY_SOLUTION,
        'a best solution' => FIND::A_BEST_SOLUTION,
        'all best solutions' => FIND::ALL_BEST_SOLUTIONS,
        'all loopless solutions' => FIND::ALL_LOOPLESS_SOLUTIONS,
    ];

    /** @var int */
    private $solutionType;

    public function __construct(int $solutionType)
    {
        $this->solutionType = $solutionType;
    }

    public static function theOnlySolution(): self
    {
        return new self(Find::THE_ONLY_SOLUTION);
    }

    public static function aBestSolution(): self
    {
        return new self(Find::A_BEST_SOLUTION);
    }

    public static function allBestSolutions(): self
    {
        return new self(Find::ALL_BEST_SOLUTIONS);
    }

    public static function allLooplessSolutions(): self
    {
        return new self(Find::ALL_LOOPLESS_SOLUTIONS);
    }

    public static function fromString(string $type): self
    {
        $type = strtolower($type);
        assert(isset(Find::MAP[$type]));
        return new self(Find::MAP[$type]);
    }

    public function singleSolution(): bool
    {
        return $this->solutionType === self::THE_ONLY_SOLUTION
            || $this->solutionType === self::A_BEST_SOLUTION;
    }

    public function onlyBest(): bool
    {
        return $this->solutionType === self::ALL_BEST_SOLUTIONS
            || $this->solutionType === self::A_BEST_SOLUTION;
    }
}
