<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Puzzle\SlidingCrates;

use RuntimeException;
use Stratadox\ImmutableCollection\ImmutableCollection;
use Stratadox\ImmutableCollection\Purging;
use function implode;
use const PHP_EOL;

final class Crates extends ImmutableCollection
{
    use Purging;

    public function __construct(Crate ...$crates)
    {
        parent::__construct(...$crates);
    }

    public function current(): Crate
    {
        return parent::current();
    }

    public function after(Push $push): self
    {
        $crates = $this->items();
        foreach ($this as $i => $crate) {
            if ($crate->id() === $push->crateId()) {
                $crates[$i] = $crate->after($push);
                break;
            }
        }
        return $this->newCopy($crates);
    }

    public function withId(string $id): Crate
    {
        foreach ($this as $crate) {
            if ($crate->id() === $id) {
                return $crate;
            }
        }
        throw new RuntimeException("No crate with id $id");
    }

    public function __toString(): string
    {
        return implode(PHP_EOL, $this->items());
    }
}
