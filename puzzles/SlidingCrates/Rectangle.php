<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use function abs;
use function current;
use function end;

final class Rectangle
{
    /** @var int */
    private $x;
    /** @var int */
    private $y;
    /** @var int */
    private $w;
    /** @var int */
    private $h;

    private function __construct(int $x, int $y, int $w, int $h)
    {
        $this->x = $x;
        $this->y = $y;
        $this->w = $w;
        $this->h = $h;
    }

    public static function fromCoordinates(array ...$coordinates): self
    {
        [$x1, $y1] = current($coordinates);
        [$x2, $y2] = end($coordinates);
        $h = abs($y2 - $y1) + 1;
        $w = abs($x2 - $x1) + 1;
        return new self($x1, $y1, $w, $h);
    }

    public function isHorizontal(): bool
    {
        return $this->w >= $this->h;
    }

    public function isVertical(): bool
    {
        return $this->h >= $this->w;
    }

    public function after(Push $push): self
    {
        $new = clone $this;
        $new->x = $push->newX($this->x);
        $new->y = $push->newY($this->y);
        return $new;
    }

    public function collidesWith(Rectangle $other): bool
    {
        return $this->x < $other->x + $other->w
            && $this->x + $this->w > $other->x
            && $this->y < $other->y + $other->h
            && $this->y + $this->h > $other->y;
    }

    public function isOutOfBounds(int $width, int $height): bool
    {
        return $this->x < 0
            || $this->x + $this->w > $width
            || $this->y < 0
            || $this->y + $this->h > $height;
    }

    public function coordinates(): array
    {
        $coordinates = [];
        for ($x = 0; $x < $this->w; ++$x) {
            for ($y = 0; $y < $this->h; ++$y) {
                $coordinates[] = [$this->x + $x, $this->y + $y];
            }
        }
        return $coordinates;
    }

    public function __toString(): string
    {
        return "($this->x, $this->y, $this->w, $this->h)";
    }
}
