<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Renderer;

use Stratadox\PuzzleSolver\Solution;
use Stratadox\PuzzleSolver\SolutionRenderer;
use function assert;
use function fopen;
use function fwrite;
use function is_resource;
use function usleep;

/**
 * PuzzleStates-to-file Renderer
 *
 * Renders each of the in-between states on the path that led to the final
 * solution to a file.
 *
 * @author Stratadox
 */
final class PuzzleStatesToFileRenderer implements SolutionRenderer
{
    /** @var resource */
    private $output;
    /** @var string */
    private $separator;
    /** @var int */
    private $timeout;

    private function __construct($outputStream, string $separator, int $timeout)
    {
        $this->output = $outputStream;
        $this->separator = $separator;
        $this->timeout = $timeout;
        assert(is_resource($outputStream));
    }

    public static function fromFilenameAndSeparator(
        string $fileName,
        string $separator,
        int $timeout = 0
    ): SolutionRenderer {
        return new self(fopen($fileName, 'wb'), $separator, $timeout);
    }

    public function render(Solution $solution): void
    {
        $puzzle = $solution->original();
        $this->log($puzzle->representation());
        usleep($this->timeout);
        foreach ($solution->moves() as $move) {
            $puzzle = $puzzle->afterMaking($move);
            $this->log($this->separator);
            $this->log($puzzle->representation());
            usleep($this->timeout);
        }
    }

    private function log(string $entry): void
    {
        fwrite($this->output, $entry);
    }
}
