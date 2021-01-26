<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\Crossing;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\Purchase;
use Stratadox\PuzzleSolver\Puzzle\WolfGoatCabbage\RiverCrossingPuzzle;

/**
 * @testdox Solving a wolf-goat-cabbage puzzle, manually
 */
class Solving_a_wolf_goat_cabbage_puzzle_manually extends TestCase
{
    /** @test */
    function not_solved_in_a_single_move()
    {
        self::assertFalse(
            RiverCrossingPuzzle::begin()
                ->afterMaking(new Crossing(true, Purchase::goat()))
                ->isSolved()
        );
    }

    /** @test */
    function manually_solving_the_puzzle_wolf_first()
    {
        self::assertTrue(RiverCrossingPuzzle::begin()
            ->afterMaking(
                new Crossing(true, Purchase::goat()),
                new Crossing(false, null),
                new Crossing(true, Purchase::wolf()),
                new Crossing(false, Purchase::goat()),
                new Crossing(true, Purchase::cabbage()),
                new Crossing(false, null),
                new Crossing(true, Purchase::goat())
            )
            ->isSolved());
    }

    /** @test */
    function manually_solving_the_puzzle_cabbage_first()
    {
        self::assertTrue(RiverCrossingPuzzle::begin()
            ->afterMaking(
                new Crossing(true, Purchase::goat()),
                new Crossing(false, null),
                new Crossing(true, Purchase::cabbage()),
                new Crossing(false, Purchase::goat()),
                new Crossing(true, Purchase::wolf()),
                new Crossing(false, null),
                new Crossing(true, Purchase::goat())
            )
            ->isSolved());
    }
}
