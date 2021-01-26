<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\Moves;
use function count;
use function preg_split;
use function str_replace;
use function str_split;
use function usort;

final class SlidingCratesPuzzle implements Puzzle
{
    private const NO_CRATE = '.';

    /** @var Room */
    private $room;
    /** @var string */
    private $target;
    /** @var Moves */
    private $moves;

    private function __construct(Room $room, string $target, Moves $moves)
    {
        $this->room = $room;
        $this->target = $target;
        $this->moves = $moves;
    }

    public static function with(Room $room, string $target): Puzzle
    {
        return new self($room, $target, Moves::none());
    }

    public static function fromString(string $crates, string $target): Puzzle
    {
        $rows = [];
        foreach (preg_split("/(\n)|(\r)/", str_replace(' ', '', $crates)) as $line) {
            $row = [];
            if (empty($line)) {
                continue;
            }
            foreach (str_split($line) as $char) {
                $row[] = $char;
            }
            $rows[] = $row;
        }
        return self::fromArray($rows, $target);
    }

    public static function fromArray(array $room, string $target): Puzzle
    {
        /** @var array[][] $crateCoordinates */
        $crateCoordinates = [];
        foreach ($room as $y => $row) {
            foreach ($row as $x => $column) {
                if ($column === self::NO_CRATE) {
                    continue;
                }
                $crateCoordinates[$column][] = [$x, $y];
            }
        }
        /** @var Crate[] $crates */
        $crates = [];
        foreach ($crateCoordinates as $id => $coordinates) {
            $crates[] = Crate::fromCoordinates($id, ...$coordinates);
        }
        usort($crates, static function (Crate $crate1, Crate $crate2): int {
            return $crate1->id() <=> $crate2->id();
        });

        return self::with(Room::withCrates(
            new Crates(...$crates),
            count($room[0]),
            count($room),
            Push::right($target)
        ), $target);
    }

    public function representation(): string
    {
        return (string) $this->room;
    }

    public function afterMaking(Move ...$moves): Puzzle
    {
        return new self(
            $this->room->afterPushing(...$moves),
            $this->target,
            $this->moves->add(...$moves)
        );
    }

    public function isSolved(): bool
    {
        return $this->room->isSolvedFor($this->target);
    }

    public function movesSoFar(): Moves
    {
        return $this->moves;
    }

    public function possibleMoves(): Moves
    {
        return new Moves(...$this->room->possiblePushes());
    }

    public function minimumDistanceToGoal(): int
    {
        return $this->room->minimumDistanceToGoalFor($this->target);
    }
}
