<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NetworkNavigation;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;
use function json_decode;

final class NetworkNavigationPuzzle implements Puzzle
{
    /** @var string */
    private $heroLocation;
    /** @var string */
    private $goalLocation;
    /** @var float[][] */
    private $network;
    /** @var Moves */
    private $moves;

    private function __construct(
        string $heroLocation,
        string $goalLocation,
        array $network,
        Moves $moves
    ) {
        $this->heroLocation = $heroLocation;
        $this->goalLocation = $goalLocation;
        $this->network = $network;
        $this->moves = $moves;
    }

    public static function fromJsonAndStartAndGoal(
        string $json,
        string $start,
        string $goal
    ): Puzzle {
        $network = [];
        foreach (json_decode($json, true) as $edge) {
            $network[$edge['from']][$edge['to']] = $edge['cost'];
        }
        return new self($start, $goal, $network, Moves::none());
    }

    public function representation(): string
    {
        return $this->heroLocation;
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        $move = end($moves);
        if (!$move instanceof Jump) {
            return $this;
        }
        $new = clone $this;
        $new->heroLocation = $move->target();
        $new->moves = $this->moves->add(...$moves);
        return $new;
    }

    public function isSolved(): bool
    {
        return $this->heroLocation === $this->goalLocation;
    }

    public function movesSoFar(): Moves
    {
        return $this->moves;
    }

    public function possibleMoves(): Moves
    {
        $moves = [];
        foreach ($this->network[$this->heroLocation] as $neighbour => $cost) {
            $moves[] = Jump::to($neighbour, $cost);
        }
        return new Moves(...$moves);
    }
}
