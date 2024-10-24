<?php
include 'Cell.php';

class World
{
    private array $grid;
    private int $rows = 5;
    private int $cols = 5;

    public function __construct($rows, $cols)
    {
        $this->rows = $rows;
        $this->cols = $cols;
        $this->grid = array(); // Grid is an array of rows that contains an array of cells (column)
        $this->createGrid();
    }

    // Create initial grid with random values (alive or dead)
    public function createGrid(): void
    {
        for ($i = 0; $i < $this->rows; $i++) { // Go through rows
            $this->grid[$i] = array(); // In this row, create an array
            for ($j = 0; $j < $this->cols; $j++) { // Go through this array
                // If random = true, randomise state of cells, else every cell's dead
                $state = rand(0, 1) == 1;
                $this->grid[$i][$j] = new Cell($state); // Create a cell with a state
            }
        }
    }

    public function displayGrid(): void
    {
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                // ⚪ = alive, ⚫ = dead
                echo $this->grid[$i][$j]->isAlive() ? '⚪' : '⚫'; // For each cell, asks it's state then draw a circle
            }
            echo PHP_EOL; // Back to line
        }
    }

    private function countAliveNeighbors($row, $col): int // For one specific cell
    {
        $aliveCount = 0;

        for ($i = -1; $i <= 1; $i++) { // Check previous to next row
            for ($j = -1; $j <= 1; $j++) { // Check previous to next column
                if ($i == 0 && $j == 0) continue; // Ignore the specific cell given
                $neighborRow = $row + $i; // $neighborRow = $row-1 OR $row OR $row+1
                $neighborCol = $col + $j; // $neighborCol = $col-1 OR $col OR $col+1

                // Check if the given neighboor is in this grid
                if ($neighborRow >= 0 && $neighborRow < $this->rows && $neighborCol >= 0 && $neighborCol < $this->cols) {
                    if ($this->grid[$neighborRow][$neighborCol]->isAlive()) { // Check state of cell
                        $aliveCount++;
                    }
                }
            }
        }

        return $aliveCount;
    }


    public function nextGeneration(): void
    {
        $newGrid = array(); // Empty array to stock the next gen info

        for ($i = 0; $i < $this->rows; $i++) {
            $newGrid[$i] = array(); // Make it two dimensional
            for ($j = 0; $j < $this->cols; $j++) {
                $aliveNeighbors = $this->countAliveNeighbors($i, $j); // For each cell, count alive neighbors


                if ($this->grid[$i][$j]->isAlive()) { // If cell is alive and ]2,3[ alive neighboors
                    if ($aliveNeighbors < 2 || $aliveNeighbors > 3) {
                        $newGrid[$i][$j] = new Cell(false); // Make it dead in new gen
                    } else {
                        $newGrid[$i][$j] = new Cell(true);
                    }
                } else {
                    if ($aliveNeighbors == 3) { // If cell is dead and 3 alive neighbors
                        $newGrid[$i][$j] = new Cell(true); // Make it alive  in new gen

                    } else {
                        $newGrid[$i][$j] = new Cell(false);
                    }
                }
            }
        }
        $this->grid = $newGrid; // New gen becomes actual gen
    }

    // Generate and display evolutions
    public function evolute(World $world, int $numGen = 3): void
    {
        $numGen = abs($numGen); // Number of generations is absolute positive

        echo PHP_EOL . "This game is about life\033[31m and death\033[0m." . PHP_EOL . "\033[32mLet's see how many survive...\033[0m" . PHP_EOL . PHP_EOL . "\033[35mInitial state\033[0m :" . PHP_EOL; // Unique output for generation 0, so the for() starts to $generation = 1
        $this->displayGrid($world, $numGen);
        sleep(1); // Wait a sec

        for ($generation = 1; $generation <= $numGen; $generation++) {
            echo PHP_EOL . "\033[0m--->\033[35m Generation $generation\033[0m :" . PHP_EOL;
            $world->nextGeneration(); // Calculate NEXT generation
            $world->displayGrid(); // Display NEW generation
            sleep(1); // Wait a sec
        }
        echo PHP_EOL . "          \033[31mThanatos had a lot of work..." . PHP_EOL . "\033[93m...and a lot of fun!" . PHP_EOL . "\033[0m___________________________\033[0m" . PHP_EOL;
    }
}
