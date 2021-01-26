<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\NetworkNavigation;

use Stratadox\PuzzleSolver\InvalidFormat;
use Stratadox\PuzzleSolver\Puzzle;
use Stratadox\PuzzleSolver\PuzzleFactory;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use const JSON_ERROR_NONE;

final class NetworkPuzzleFactory implements PuzzleFactory
{
    public static function make(): PuzzleFactory
    {
        return new self();
    }

    public function fromString(string $puzzle): Puzzle
    {
        $network = [];
        $data = json_decode($puzzle, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw InvalidFormat::couldNotLoad('network', $puzzle, json_last_error_msg());
        }

        foreach ($data['edges'] as $edge) {
            $network[$edge['from']][$edge['to']] = $edge['cost'];
        }

        return NetworkNavigationPuzzle::fromArrayAndStartAndGoal(
            $network,
            $data['start'],
            $data['goal']
        );
    }
}
