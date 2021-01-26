<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage;

use Stratadox\PuzzleSolver\Moves;
use function assert;
use function sprintf;

final class Farmer
{
    /** @var Riverbank */
    private $currentBank;
    /** @var Riverbank */
    private $otherBank;

    public function __construct(
        Riverbank $currentBank,
        Riverbank $otherBank
    ) {
        $this->currentBank = $currentBank;
        $this->otherBank = $otherBank;
    }

    public function hasMovedAllPurchasesAlong(): bool
    {
        return $this->currentBank->isGoal() && $this->currentBank->isFull();
    }

    public static function withWolfGoatAndCabbage(): self
    {
        return new self(
            new Riverbank(true, Purchase::cabbage(), Purchase::goat(), Purchase::wolf()),
            new Riverbank(false)
        );
    }

    public function possibleCrossings(): Moves
    {
        return Crossing::allCrossingsFor($this->currentBank)
            ->filterWith(function (Crossing $crossing) {
                return $this->currentBank->canTakeAway($crossing->bringAlong());
            });
    }

    public function after(Crossing $crossing): self
    {
        assert($this->currentBank->canTakeAway($crossing->bringAlong()));
        return new self(
            $this->otherBank->with($crossing->bringAlong()),
            $this->currentBank->without($crossing->bringAlong())
        );
    }

    public function __toString(): string
    {
        return sprintf(
            'Current bank: %s; Other bank: %s',
            $this->currentBank,
            $this->otherBank
        );
    }
}
