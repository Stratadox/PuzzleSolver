<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\SearchStrategy;

use Stratadox\PuzzleSolver\Puzzle;
use function fopen;
use function str_repeat;
use const PHP_EOL;

/**
 * Factory for @see DebugLogger
 *
 * @author Stratadox
 */
final class DebugLoggerFactory implements SearchStrategyFactory
{
    /** @var SearchStrategyFactory */
    private $factory;
    /** @var int */
    private $timeout;
    /** @var string */
    private $output;

    private function __construct(
        SearchStrategyFactory $factory,
        int $timeout,
        string $output
    ) {
        $this->factory = $factory;
        $this->timeout = $timeout;
        $this->output = $output;
    }

    public static function withTimeout(
        int $timeout,
        SearchStrategyFactory $factory,
        string $output = 'php://stdout'
    ): SearchStrategyFactory {
        return new self($factory, $timeout, $output);
    }

    public function begin(Puzzle $puzzle): SearchStrategy
    {
        return new DebugLogger(
            $this->factory->begin($puzzle),
            $this->timeout,
            str_repeat(PHP_EOL, 20),
            fopen($this->output, 'wb')
        );
    }
}
