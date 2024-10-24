<?php
class Cell
{
    public bool $state; // whether cell is alive or dead

    // Constructor
    public function __construct(bool $state = false)
    {
        $this->state = $state;
    }
    // Methods
    public function toggle()
    {
        $this->state = !$this->state;
    }

    public function isAlive()
    {
        return $this->state;
    }
}


$a = new Cell();
