<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use function array_diff;
use function array_merge;
use function count;
use function implode;
use function sprintf;

final class Riverbank
{
    /** @var bool */
    private $isStart;
    /** @var Purchase[] */
    private $purchases;

    public function __construct(bool $isStart, Purchase ...$purchases)
    {
        $this->isStart = $isStart;
        $this->purchases = $purchases;
    }

    /** @return Purchase[] */
    public function purchases(): array
    {
        return $this->purchases;
    }

    public function isStart(): bool
    {
        return $this->isStart;
    }

    public function isGoal(): bool
    {
        return !$this->isStart;
    }

    public function isFull(): bool
    {
        return count($this->purchases) === 3;
    }

    public function canTakeAway(?Purchase $bringAlong): bool
    {
        $remaining = array_diff($this->purchases, [$bringAlong]);
        foreach ($remaining as $theOne) {
            foreach ($remaining as $theOther) {
                if ($theOne->eats($theOther)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function with(?Purchase $purchase): self
    {
        if (!$purchase) {
            return $this;
        }
        return new self(
            $this->isStart,
            ...array_merge($this->purchases, [$purchase])
        );
    }

    public function without(?Purchase $purchase): self
    {
        if (!$purchase) {
            return $this;
        }
        return new self(
            $this->isStart,
            ...array_diff($this->purchases, [$purchase])
        );
    }

    public function __toString(): string
    {
        return sprintf(
            '%s bank with %s',
            $this->isStart ? 'Start' : 'Goal',
            implode(', ', $this->purchases)
        );
    }
}
