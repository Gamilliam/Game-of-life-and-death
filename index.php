<?php
include 'World.php';

$world = new World(8, 20); // Create a grid with specific dimensions

$world->evolute($world, 10);
//$world->evolute(new World(3, 10), 1); // That's a amusing but annoying bug