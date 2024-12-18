<?php

define("width", 71);
define("heigth", 71);
define("num_bytes", 12);

$lines = file('in.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
$bytes = [];
foreach ($lines as $line) {
    preg_match('/(\d+),(\d+)/', $line, $matches);
    $bytes[] = [(int)$matches[1], (int)$matches[2]];
}

class MinHeap extends SplMinHeap {

    protected function compare($v1, $v2) {
        if ($v1['f'] < $v2['f']) {
            return 1;
        } else if ($v1['f'] === $v2['f']) {
            return 0;
        } else {
            return -1;
        }
    }
}

function h($node) {
    return abs((width - 1) - $node[0]) + abs((heigth - 1) - $node[1]);
}

function a_star($map) {
    $heap = new MinHeap();
    $heap->insert(['node' => [0,0], 'g' => 0, 'f' => h([0,0])]);
    $directions = [
        [0, 1],
        [0, -1],
        [1, 0],
        [-1, 0],
    ];
    $closed = [];

    while (!$heap->isEmpty()) {
        $c = $heap->extract();

        [$y, $x] = $c['node'];

        if ([$y, $x] === [heigth - 1, width - 1]) {
            return $c['g'];
        }

        $closed["$y,$x"] = true;

        foreach ($directions as [$dy, $dx]) {
            $ny = $y + $dy;
            $nx = $x + $dx;

            if (isset($map[$ny][$nx]) && ($map[$ny][$nx] === '.') && !isset($closed["$ny,$nx"])) {
                $nn = ['node' => [$ny, $nx], 'g' => $c['g'] + 1, 'f' => $c['g'] + 1 + h([$ny, $nx])];
                $heap->insert($nn);
            }
        }
    }

    return -1;
}

function partA($bytes) {
    $map = [];

    for ($i=0; $i < heigth; $i++) {
        $row = array_fill(0, width, '.');
        $map[] = $row;
    }

    for ($i=0; $i < num_bytes; $i++) {
        $byte = $bytes[$i];
        $map[$byte[1]][$byte[0]] = '#';
    }

    return a_star($map);
}

function partB($bytes) {

    for ($num_bytes = 12; $num_bytes < 10000; $num_bytes++) {
        $map = [];

        for ($i=0; $i < heigth; $i++) {
            $row = array_fill(0, width, '.');
            $map[] = $row;
        }

        for ($i=0; $i < $num_bytes; $i++) {
            $byte = $bytes[$i];
            $map[$byte[1]][$byte[0]] = '#';
        }

        if (a_star($map) == -1) {
            return $bytes[$num_bytes - 1];
        }
    }
}

// $resA = partA($bytes);
// echo "Part A: $resA\n";

$resB = partB($bytes);
echo "Part B:";
print_r($resB);

?>
