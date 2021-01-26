<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use Stratadox\PuzzleSolver\Moves;
use function array_fill;
use function implode;
use function str_repeat;
use const PHP_EOL;

final class Room
{
    /** @var Crates */
    private $crates;
    /** @var int */
    private $width;
    /** @var int */
    private $height;
    /** @var Push */
    private $winningPush;

    private function __construct(
        Crates $crates,
        int $width,
        int $height,
        Push $winningPush
    ) {
        $this->crates = $crates;
        $this->width = $width;
        $this->height = $height;
        $this->winningPush = $winningPush;
    }

    public static function withCrates(
        Crates $crates,
        int $width,
        int $height,
        Push $winningPush
    ): self {
        return new self($crates, $width, $height, $winningPush);
    }

    public function afterPushing(Push ...$pushes): self
    {
        $new = clone $this;
        foreach ($pushes as $push) {
            $new->crates = $new->crates->after($push);
        }
        return $new;
    }

    public function isSolvedFor(string $target): bool
    {
        return $this->crates->withId($target)->wouldMoveOutOfBounds(
            $this->winningPush,
            $this->width,
            $this->height
        );
    }

    public function minimumDistanceToGoalFor(string $target): int
    {
        return $this->crates->withId($target)->movesUntilOutOfBounds(
            $this->winningPush,
            $this->width,
            $this->height
        );
    }

    public function possiblePushes(): Moves
    {
        return Push::allFor($this->crates)
            ->filterWith(function (Push $push): bool {
                return $this->crates->withId($push->crateId())->canPush(
                    $push,
                    $this->crates,
                    $this->width,
                    $this->height
                );
            });
    }

    public function __toString(): string
    {
        $lines = array_fill(0, $this->height, str_repeat('. ', $this->width));
        foreach ($this->crates as $crate) {
            foreach ($crate->coordinates() as [$x, $y]) {
                $lines[$y][$x * 2] = $crate->id();
            }
        }
        return implode(PHP_EOL, $lines);
    }
}
