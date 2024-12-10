<?php

require 'queue.php';

$map = array_map(
    fn($line) => array_map('intval', str_split($line)),
    file('in.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
);

function get_trailheads() {
    global $map;

    $trailheads = [];

    foreach ($map as $i => $row) {
        foreach ($row as $j => $value) {
            if ($value === 0) {
                $trailheads[] = [$i, $j];
            }
        }
    }

    return $trailheads;
}

function partA() {
    global $map;

    $sum = 0;
    $trailheads = get_trailheads();

    foreach ($trailheads as $th) {
        $queue = new Queue;
        $curr_trails = 0;
        $in_queue = [];

        $queue->enqueue($th);
        while (!$queue->isEmpty()) {
            [$y, $x] = $queue->dequeue();

            $val = $map[$y][$x];
            if ($val == 9) {
                $curr_trails++;
                continue;
            }

            if (isset($map[$y - 1][$x]) && ($map[$y - 1][$x] == ($val + 1))) {
                if (!isset($in_queue[($y - 1) . ",$x"])) {
                    $queue->enqueue([$y - 1, $x]);
                    $in_queue[($y - 1) . ",$x"] = true;
                }
            }
            if (isset($map[$y][$x + 1]) && ($map[$y][$x + 1] == ($val + 1))) {
                if (!isset($in_queue["$y," . ($x + 1)])) {
                    $queue->enqueue([$y, $x + 1]);
                    $in_queue["$y," . ($x + 1)] = true;
                }
            }
            if (isset($map[$y + 1][$x]) && ($map[$y + 1][$x] == ($val + 1))) {
                if (!isset($in_queue[($y + 1) . ",$x"])) {
                    $queue->enqueue([$y + 1, $x]);
                    $in_queue[($y + 1) . ",$x"] = true;
                }
            }
            if (isset($map[$y][$x - 1]) && ($map[$y][$x - 1] == ($val + 1))) {
                if (!isset($in_queue["$y," . ($x - 1)])) {
                    $queue->enqueue([$y, $x - 1]);
                    $in_queue["$y," . ($x - 1)] = true;
                }
            }
        }

        $sum += $curr_trails;
    }

    return $sum;
}

function partB() {
    global $map;

    $sum = 0;
    $trailheads = get_trailheads();

    foreach ($trailheads as $th) {
        $queue = new Queue;
        $curr_trails = 0;
        $in_queue = [];

        $queue->enqueue($th);
        while (!$queue->isEmpty()) {
            [$y, $x] = $queue->dequeue();

            $val = $map[$y][$x];
            if ($val == 9) {
                $curr_trails++;
                continue;
            }

            if (isset($map[$y - 1][$x]) && ($map[$y - 1][$x] == ($val + 1))) {
                $queue->enqueue([$y - 1, $x]);
            }
            if (isset($map[$y][$x + 1]) && ($map[$y][$x + 1] == ($val + 1))) {
                $queue->enqueue([$y, $x + 1]);
            }
            if (isset($map[$y + 1][$x]) && ($map[$y + 1][$x] == ($val + 1))) {
                $queue->enqueue([$y + 1, $x]);
            }
            if (isset($map[$y][$x - 1]) && ($map[$y][$x - 1] == ($val + 1))) {
                $queue->enqueue([$y, $x - 1]);
            }
        }

        $sum += $curr_trails;
    }

    return $sum;
}

$resA = partA();
echo "Result A: $resA\n";

$resB = partB();
echo "Result B: $resB\n";

?>
