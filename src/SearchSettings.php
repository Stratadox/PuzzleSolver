<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver;

final class SearchSettings
{
    /** @var string|null */
    private $loggingFile;
    /** @var string */
    private $logSeparator;
    /** @var int */
    private $iterationInterval;

    public function __construct(
        ?string $loggingFile,
        string $logSeparator,
        int $iterationInterval
    ) {
        $this->loggingFile = $loggingFile;
        $this->logSeparator = $logSeparator;
        $this->iterationInterval = $iterationInterval;
    }

    public static function defaults(): self
    {
        return new self(null, '', 0);
    }

    public function loggingFile(): ?string
    {
        return $this->loggingFile;
    }

    public function logSeparator(): string
    {
        return $this->logSeparator;
    }

    public function iterationInterval(): int
    {
        return $this->iterationInterval;
    }
}
