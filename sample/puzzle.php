<?php declare(strict_types=1);

use Stratadox\PuzzleSolver\Find;
use Stratadox\PuzzleSolver\NoSolution;
use Stratadox\PuzzleSolver\PuzzleDescription;
use Stratadox\PuzzleSolver\PuzzleFactory;
use Stratadox\PuzzleSolver\PuzzleSolverFactory;
use Stratadox\PuzzleSolver\Renderer\MovesToFileRenderer;
use Stratadox\PuzzleSolver\Renderer\PuzzleStatesToFileRenderer;
use Stratadox\PuzzleSolver\SearchSettings;
use Stratadox\PuzzleSolver\SolutionRenderer;
use Stratadox\PuzzleSolver\Solutions;

require dirname(__DIR__) . '/vendor/autoload.php';

const OUT = 'php://stdout';

$directory = dirname(__DIR__) . str_replace(
    '/',
    DIRECTORY_SEPARATOR,
    '/sample/levels/%s/'
);

$json = file_get_contents(dirname(__DIR__) . '/sample/puzzles.json');
$puzzleData = json_decode($json, true);
$factories = [];
foreach ($puzzleData['factories'] as $type => $factory) {
    assert(is_callable($factory));
    $factories[$type] = $factory();
}

$sep = str_repeat(PHP_EOL, 40);
$time = 600000;
$puzzleInfo = [];
foreach ($puzzleData['puzzles'] as $puzzle) {
    $dir = sprintf($directory, $puzzle['type']);
    $description = new PuzzleDescription(
        Find::fromString($puzzle['find']),
        $puzzle['variable_cost'] ?? false,
        $puzzle['exhausting'] ?? false,
        isset($puzzle['heuristic']) ? new $puzzle['heuristic']() : null
    );
    $renderer = ($puzzle['display'] ?? 'states') === 'states' ?
        PuzzleStatesToFileRenderer::fromFilenameAndSeparator(OUT, $sep, $time) :
        MovesToFileRenderer::fromFilenameAndSeparator(OUT, $sep, $time);

    $puzzleInfo[$puzzle['name']] = [];
    foreach (scandir($dir) as $file) {
        if (substr($file, -4) !== '.txt') {
            continue;
        }
        $puzzleInfo[$puzzle['name']][$file[0]] = [
            'levelFile' => $dir . $file,
            'factory' => $factories[$puzzle['type']],
            'description' => $description,
            'renderer' => $renderer,
            'isDefault' => (($puzzle['default'] ?? '') . '.txt') ===  $file,
            'isTheOnlyOne' => (bool) ($puzzle['level'] ?? false),
        ];
    }
}

$puzzleName = choosePuzzle($puzzleInfo);
echo PHP_EOL . 'Selected puzzle: ' . $puzzleName . PHP_EOL . PHP_EOL;
$levelTag = chooseLevel($puzzleInfo, $puzzleName);
$puzzleData = $puzzleInfo[$puzzleName][$levelTag];
$level = file_get_contents($puzzleData['levelFile']);
if (!empty($level)) {
    echo 'The chosen level is: ' . PHP_EOL . PHP_EOL . $level . PHP_EOL . PHP_EOL;
}
$settings = chooseSettings();
$solutions = solvePuzzle(
    $puzzleData['factory'],
    $puzzleData['description'],
    $settings,
    $level
);
render($solutions, $puzzleData['renderer']);

function choosePuzzle(array $puzzleInfo): string
{
    $puzzleChoices = array_keys($puzzleInfo);
    $welcome = [
        'Welcome to the universal puzzle solver!',
        'The following puzzles are installed:'
    ];
    foreach ($puzzleChoices as $n => $name) {
        $welcome[] = sprintf('Type %d: %s', $n, $name);
    }

    echo implode(PHP_EOL, $welcome) . PHP_EOL . PHP_EOL;

    do {
        $puzzleChoice = readline('Which puzzle would you like to solve? ');
    } while (!ctype_digit($puzzleChoice));

    return $puzzleChoices[$puzzleChoice];
}

function chooseLevel(array $puzzleInfo, string $name): string
{
    $welcome = [
        'The following levels are available:'
    ];
    foreach ($puzzleInfo[$name] as $letter => $puzzleDate) {
        if ($puzzleDate['isTheOnlyOne']) {
            return $letter;
        }
        $n = str_replace(' ', '', substr(basename($puzzleDate['levelFile']), 2, -4));
        $welcome[] = sprintf('%s: %s', $letter, $n);
    }

    echo implode(PHP_EOL, $welcome) . PHP_EOL . PHP_EOL;

    do {
        $levelChoice = strtoupper(readline('Which level would you like to solve? '));
    } while (strlen($levelChoice) !== 1);

    return $levelChoice;
}

function chooseSettings()
{
    $visual = readline('Would you like to visualise the search? [Y]/n ');
    if ($visual !== '' && strtolower($visual) !== 'y') {
        return SearchSettings::defaults();
    }
    return new SearchSettings(OUT, str_repeat(PHP_EOL, 40), 80000);
}

function solvePuzzle(
    PuzzleFactory $puzzleFactory,
    PuzzleDescription $description,
    SearchSettings $settings,
    string $level
): Solutions {
    $puzzle = $puzzleFactory->fromString($level);

    $solver = PuzzleSolverFactory::make()
        ->forAPuzzleWith($description, $settings);

    try {
        return $solver->solve($puzzle);
    } catch (NoSolution $exception) {
        die($exception->getMessage());
    }
}


function render(Solutions $solutions, SolutionRenderer $renderer)
{
    $nl = str_repeat(PHP_EOL, 40);
    echo $nl . 'Solved!' . str_repeat(PHP_EOL, 5);
    sleep(1);
    if (count($solutions) === 1) {
        $renderer->render($solutions[0]);
        echo $nl . $solutions[0]->state()->representation();
    } else {
        foreach ($solutions as $i => $solution) {
            echo sprintf('%sSolution %d: %s', $nl, $i + 1, str_repeat(PHP_EOL, 5));
            sleep(1);
            echo $nl;
            $renderer->render($solution);
            echo $nl . $solution->state()->representation();
        }
    }
}
