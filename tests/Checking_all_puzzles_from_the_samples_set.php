<?php declare(strict_types=1);

namespace Stratadox\PuzzleSolver\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\PuzzleDescription;
use Stratadox\PuzzleSolver\PuzzleFactory;
use Stratadox\PuzzleSolver\PuzzleSolverFactory;
use Stratadox\PuzzleSolver\SearchSettings;
use Stratadox\PuzzleSolver\SolverFactory;
use function dirname;
use function file_get_contents;
use function json_decode;
use function scandir;
use function sprintf;
use function substr;
use const DIRECTORY_SEPARATOR;

/**
 * @testdox Checking all puzzles from the samples set
 */
class Checking_all_puzzles_from_the_samples_set extends TestCase
{
    private const LVL = __DIR__ . '/../sample/levels/%s/%s.txt';
    /** @var SolverFactory */
    private $solverFactory;
    /** @var SearchSettings */
    private $settings;

    protected function setUp(): void
    {
        $this->solverFactory = PuzzleSolverFactory::make();
        $this->settings = SearchSettings::defaults();
    }

    private function solveThePuzzle(
        string $levelFile,
        PuzzleFactory $factory,
        PuzzleDescription $description
    ): void {
        $solver = $this->solverFactory->forAPuzzleWith($description, $this->settings);
        $puzzle = $factory->fromString(file_get_contents($levelFile));

        $solutions = $solver->solve($puzzle);

        self::assertNotEmpty($solutions);
        self::assertNotEmpty($solutions[0]->moves());
    }

    /**
     * @test
     * @dataProvider defaultSamplePuzzleData
     */
    function solving_the_default_sample_puzzle(
        string $levelFile,
        PuzzleFactory $factory,
        PuzzleDescription $description
    ) {
        $this->solveThePuzzle($levelFile, $factory, $description);
    }

    /**
     * @test
     * @dataProvider allSamplePuzzleData
     * @group thorough
     */
    function solving_the_sample_puzzle(
        string $levelFile,
        PuzzleFactory $factory,
        PuzzleDescription $description
    ) {
        $this->solveThePuzzle($levelFile, $factory, $description);
    }

    public static function allSamplePuzzleData(): array
    {
        $puzzleInfo = [];
        foreach (self::defaultSamplePuzzleData() as $name => $puzzle) {
            $dir = dirname($puzzle['levelFile']);
            foreach (scandir($dir) as $file) {
                if (substr($file, -4) !== '.txt') {
                    continue;
                }
                $puzzleInfo[$name . ' - level ' . $file[0]] = [
                    'levelFile' => $dir . DIRECTORY_SEPARATOR . $file,
                    'factory' => $puzzle['factory'],
                    'description' => $puzzle['description'],
                ];
            }
        }
        return $puzzleInfo;
    }

    public static function defaultSamplePuzzleData(): array
    {
        $json = file_get_contents(dirname(__DIR__) . '/sample/puzzles.json');
        $puzzleData = json_decode($json, true);
        $factories = [];
        foreach ($puzzleData['factories'] as $type => $factory) {
            $factories[$type] = $factory();
        }

        $puzzleInfo = [];
        foreach ($puzzleData['puzzles'] as $puzzle) {
            $puzzleInfo[$puzzle['name']] = [
                'levelFile' => sprintf(self::LVL, $puzzle['type'], $puzzle['default'] ?? $puzzle['level']),
                'factory' => $factories[$puzzle['type']],
                'description' => new PuzzleDescription(
                    Find::fromString($puzzle['find']),
                    $puzzle['variable_cost'] ?? false,
                    $puzzle['exhausting'] ?? false,
                    isset($puzzle['heuristic']) ? new $puzzle['heuristic']() : null
                )
            ];
        }

        return $puzzleInfo;
    }
}
