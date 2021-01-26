<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

/**
 * Puzzle Description
 *
 * Value object used to describe some core characteristics of the puzzle to
 * solve; contains information about the kind and number of solutions we're
 * looking for, whether the moves could turn in circles, whether all moves are
 * of equal cost, etc.
 *
 * @author Stratadox
 */
final class PuzzleDescription
{
    /** @var Find */
    private $goal;
    /** @var bool */
    private $weightedMoves;
    /** @var bool */
    private $exhausting;
    /** @var Heuristic|null */
    private $heuristic;

    public function __construct(
        Find $goal,
        bool $weightedMoves,
        bool $exhausting,
        ?Heuristic $heuristic
    ) {
        $this->goal = $goal;
        $this->weightedMoves = $weightedMoves;
        $this->exhausting = $exhausting;
        $this->heuristic = $heuristic;
    }

    public function isWeightedMoves(): bool
    {
        return $this->weightedMoves;
    }

    public function isExhausting(): bool
    {
        return $this->exhausting;
    }

    public function heuristic(): ?Heuristic
    {
        return $this->heuristic;
    }

    public function singleSolution(): bool
    {
        return $this->goal->singleSolution();
    }

    public function onlyBest(): bool
    {
        return $this->goal->onlyBest();
    }
}
