<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

final class Crate
{
    /** @var string */
    private $id;
    /** @var Rectangle */
    private $rectangle;
    /** @var bool */
    private $canSlideHorizontally;
    /** @var bool */
    private $canSlideVertically;

    public function __construct(
        string $id,
        Rectangle $rectangle,
        bool $canSlideHorizontally,
        bool $canSlideVertically
    ) {
        $this->id = $id;
        $this->rectangle = $rectangle;
        $this->canSlideHorizontally = $canSlideHorizontally;
        $this->canSlideVertically = $canSlideVertically;
    }

    public static function fromCoordinates(
        string $id,
        array ...$coordinates
    ): self {
        return self::fromIdAndRectangle(
            $id,
            Rectangle::fromCoordinates(...$coordinates)
        );
    }

    public static function fromIdAndRectangle(
        string $id,
        Rectangle $rectangle
    ): self {
        return new self(
            $id,
            $rectangle,
            $rectangle->isHorizontal(),
            $rectangle->isVertical()
        );
    }

    public function after(Push $push): self
    {
        $new = clone $this;
        $new->rectangle = $new->rectangle->after($push);
        return $new;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function wouldMoveOutOfBounds(
        Push $push,
        int $width,
        int $height
    ): bool {
        return $this->rectangle->after($push)->isOutOfBounds($width, $height);
    }

    public function movesUntilOutOfBounds(
        Push $push,
        int $width,
        int $height
    ): int {
        $i = 0;
        $rectangle = $this->rectangle;
        while (!$rectangle->isOutOfBounds($width, $height)) {
            ++$i;
            $rectangle = $rectangle->after($push);
        }
        return $i;
    }

    public function canPush(
        Push $push,
        Crates $obstacles,
        int $width,
        int $height
    ): bool {
        if (!$this->canMoveInDirectionOf($push)) {
            return false;
        }
        $rectangle = $this->rectangle->after($push);
        return !$rectangle->isOutOfBounds($width, $height)
            && $this->canMoveUnhindered($rectangle, $obstacles->remove($this));
    }

    public function coordinates(): array
    {
        return $this->rectangle->coordinates();
    }

    public function __toString(): string
    {
        return "{$this->id} @ {$this->rectangle}";
    }

    private function canMoveInDirectionOf(Push $push): bool
    {
        return (!$push->isHorizontal() || $this->canSlideHorizontally)
            && (!$push->isVertical() || $this->canSlideVertically);
    }

    private function canMoveUnhindered(
        Rectangle $movedRectangle,
        Crates $obstacles
    ): bool {
        foreach ($obstacles as $obstacle) {
            if ($obstacle->rectangle->collidesWith($movedRectangle)) {
                return false;
            }
        }
        return true;
    }
}
