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
 * Moves-to-file Renderer
 *
 * Renders each of the moves that led to the final solution state to a file.
 *
 * @author Stratadox
 */
final class MovesToFileRenderer implements SolutionRenderer
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
        foreach ($solution->moves() as $i => $move) {
            if ($i) {
                $this->log($this->separator);
            }
            $this->log((string) $move);
            usleep($this->timeout);
        }
    }

    private function log(string $entry): void
    {
        fwrite($this->output, $entry);
    }
}
