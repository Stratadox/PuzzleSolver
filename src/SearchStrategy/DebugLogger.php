<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;
use function assert;
use function fwrite;
use function is_resource;
use function usleep;

/**
 * Debug logger
 *
 * Debug logger is a visualiser for the puzzle solver. Each time a new candidate
 * is taken under consideration, the debug logger logs it to the output stream.
 *
 * Decorator for other search strategies.
 *
 * @author Stratadox
 */
final class DebugLogger implements SearchStrategy
{
    /** @var SearchStrategy */
    private $search;
    /** @var int */
    private $timeout;
    /** @var string */
    private $separator;
    /** @var resource */
    private $output;

    public function __construct(
        SearchStrategy $search,
        int $timeout,
        string $separator,
        $outputStream
    ) {
        $this->search = $search;
        $this->timeout = $timeout;
        $this->separator = $separator;
        assert(is_resource($outputStream));
        $this->output = $outputStream;
    }

    public function isOngoing(): bool
    {
        return $this->search->isOngoing();
    }

    public function consider(Puzzle $puzzle): bool
    {
        return $this->search->consider($puzzle);
    }

    public function nextCandidate(): Puzzle
    {
        $puzzle = $this->search->nextCandidate();
        $this->log($puzzle->representation());
        usleep($this->timeout);
        $this->log($this->separator);
        return $puzzle;
    }

    private function log(string $entry): void
    {
        fwrite($this->output, $entry);
    }
}
