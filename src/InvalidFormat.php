<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

use RuntimeException;
use function sprintf;
use const PHP_EOL;

final class InvalidFormat extends RuntimeException implements PuzzleCreationFailure
{
    public static function couldNotLoad(string $puzzleType, string $input, string $error = ''): self
    {
        return new self(sprintf(
            'Invalid format. Could not load the %s from input:%s%s%s%s',
            $puzzleType,
            PHP_EOL,
            $input,
            PHP_EOL,
            $error
        ));
    }
}
