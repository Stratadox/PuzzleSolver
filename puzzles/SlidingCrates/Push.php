<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use JsonSerializable;
use Stratadox\PuzzleSolver\Move;
use Stratadox\PuzzleSolver\Moves;
use function in_array;

final class Push implements Move, JsonSerializable
{
    private const LEFT = 'left';
    private const RIGHT = 'right';
    private const UP = 'up';
    private const DOWN = 'down';

    private const HORIZONTAL = [self::LEFT, self::RIGHT];
    private const VERTICAL = [self::UP, self::DOWN];

    /** @var string */
    private $direction;
    /** @var string */
    private $crateId;

    public function __construct(string $direction, string $crateId)
    {
        $this->direction = $direction;
        $this->crateId = $crateId;
    }

    public static function allFor(Crates $crates): Moves
    {
        $moves = [];
        foreach ($crates as $crate) {
            $moves[] = Push::left($crate->id());
            $moves[] = Push::right($crate->id());
            $moves[] = Push::up($crate->id());
            $moves[] = Push::down($crate->id());
        }
        return new Moves(...$moves);
    }

    public static function left(string $rectangle): self
    {
        return new self(self::LEFT, $rectangle);
    }

    public static function right(string $rectangle): self
    {
        return new self(self::RIGHT, $rectangle);
    }

    public static function up(string $rectangle): self
    {
        return new self(self::UP, $rectangle);
    }

    public static function down(string $rectangle): self
    {
        return new self(self::DOWN, $rectangle);
    }

    public function crateId(): string
    {
        return $this->crateId;
    }

    public function isHorizontal(): bool
    {
        return in_array($this->direction, self::HORIZONTAL);
    }

    public function isVertical(): bool
    {
        return in_array($this->direction, self::VERTICAL);
    }

    public function newX(int $oldX): int
    {
        switch ($this->direction) {
            case self::RIGHT: return $oldX + 1;
            case self::LEFT: return $oldX - 1;
            default: return $oldX;
        }
    }

    public function newY(int $oldY): int
    {
        switch ($this->direction) {
            case self::UP: return $oldY - 1;
            case self::DOWN: return $oldY + 1;
            default: return $oldY;
        }
    }

    public function __toString(): string
    {
        return "Push {$this->crateId} {$this->direction}";
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->crateId,
            'direction' => $this->direction,
        ];
    }
}
