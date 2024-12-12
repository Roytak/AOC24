<?php

$lines = array_map('str_split', file('in.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

function partA($lines) {
    $plots = [];
    $regions = [];
    $idx = 0;
    $sum = 0;

    foreach ($lines as $i => $line) {
        foreach ($line as $j => $value) {
            $regions[$i][$j] = $idx;
            $idx++;
        }
    }

    do {
        $changed = false;

        foreach ($lines as $i => $line) {
            foreach ($line as $j => $value) {
                if ((isset($lines[$i - 1][$j])) && ($lines[$i - 1][$j] == $value) && ($regions[$i - 1][$j] > $regions[$i][$j])) {
                    $regions[$i - 1][$j] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i + 1][$j])) && ($lines[$i + 1][$j] == $value) && ($regions[$i + 1][$j] > $regions[$i][$j])) {
                    $regions[$i + 1][$j] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i][$j - 1])) && ($lines[$i][$j - 1] == $value) && ($regions[$i][$j - 1] > $regions[$i][$j])) {
                    $regions[$i][$j - 1] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i][$j + 1])) && ($lines[$i][$j + 1] == $value) && ($regions[$i][$j + 1] > $regions[$i][$j])) {
                    $regions[$i][$j + 1] = $regions[$i][$j];
                    $changed = true;
                }
            }
        }
    } while ($changed);

    foreach ($lines as $i => $line) {
        foreach ($line as $j => $value) {
            if (!isset($plots[$regions[$i][$j]])) {
                $plots[$regions[$i][$j]] = ["area" => 0, "perimeter" => 0];
            }
            $plots[$regions[$i][$j]]["area"]++;

            if ((!isset($lines[$i - 1][$j])) || ($lines[$i - 1][$j] != $value)) {
                $plots[$regions[$i][$j]]["perimeter"]++;
            }
            if ((!isset($lines[$i + 1][$j])) || ($lines[$i + 1][$j] != $value)) {
                $plots[$regions[$i][$j]]["perimeter"]++;
            }
            if ((!isset($lines[$i][$j - 1])) || ($lines[$i][$j - 1] != $value)) {
                $plots[$regions[$i][$j]]["perimeter"]++;
            }
            if ((!isset($lines[$i][$j + 1])) || ($lines[$i][$j + 1] != $value)) {
                $plots[$regions[$i][$j]]["perimeter"]++;
            }
        }
    }

    foreach ($plots as $plot) {
        $sum += $plot["area"] * $plot["perimeter"];
    }

    return $sum;
}

function partB($lines) {
    $plots = [];
    $regions = [];
    $idx = 0;
    $sum = 0;

    foreach ($lines as $i => $line) {
        foreach ($line as $j => $value) {
            $regions[$i][$j] = $idx;
            $idx++;
        }
    }

    do {
        $changed = false;

        foreach ($lines as $i => $line) {
            foreach ($line as $j => $value) {
                if ((isset($lines[$i - 1][$j])) && ($lines[$i - 1][$j] == $value) && ($regions[$i - 1][$j] > $regions[$i][$j])) {
                    $regions[$i - 1][$j] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i + 1][$j])) && ($lines[$i + 1][$j] == $value) && ($regions[$i + 1][$j] > $regions[$i][$j])) {
                    $regions[$i + 1][$j] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i][$j - 1])) && ($lines[$i][$j - 1] == $value) && ($regions[$i][$j - 1] > $regions[$i][$j])) {
                    $regions[$i][$j - 1] = $regions[$i][$j];
                    $changed = true;
                }
                if ((isset($lines[$i][$j + 1])) && ($lines[$i][$j + 1] == $value) && ($regions[$i][$j + 1] > $regions[$i][$j])) {
                    $regions[$i][$j + 1] = $regions[$i][$j];
                    $changed = true;
                }
            }
        }
    } while ($changed);

    foreach ($lines as $i => $line) {
        foreach ($line as $j => $value) {
            if (!isset($plots[$regions[$i][$j]])) {
                $plots[$regions[$i][$j]] = ["area" => 0, "perimeter" => 0];
            }
            $plots[$regions[$i][$j]]["area"]++;
        }
    }

    $uniqueRegions = array_values(array_unique(array_merge(...$regions)));

    foreach ($uniqueRegions as $r) {
        foreach ($lines as $i => $line) {
            $seen_up = false;
            $seen_down = false;

            foreach ($line as $j => $value) {
                if ($r != $regions[$i][$j]) {
                    continue;
                }

                if ((!$seen_up) && ((!isset($lines[$i - 1][$j]) || ($regions[$i - 1][$j] != $regions[$i][$j])))) {
                    $seen_up = true;
                    $plots[$regions[$i][$j]]["perimeter"]++;
                }
                if ((!$seen_down) && ((!isset($lines[$i + 1][$j]) || ($regions[$i + 1][$j] != $regions[$i][$j])))) {
                    $seen_down = true;
                    $plots[$regions[$i][$j]]["perimeter"]++;
                }
                if (((isset($lines[$i][$j + 1]) && ($regions[$i][$j + 1] != $regions[$i][$j])))) {
                    $seen_up = false;
                    $seen_down = false;
                }
                if ((isset($lines[$i - 1][$j]) && ($regions[$i - 1][$j] != $regions[$i][$j])) && (isset($lines[$i - 1][$j + 1]) && ($regions[$i - 1][$j + 1] == $regions[$i][$j]))) {
                    $seen_up = false;
                }
                if ((isset($lines[$i + 1][$j]) && ($regions[$i + 1][$j] != $regions[$i][$j])) && (isset($lines[$i + 1][$j + 1]) && ($regions[$i + 1][$j + 1] == $regions[$i][$j]))) {
                    $seen_down = false;
                }
            }
        }

        foreach (array_keys($lines[0]) as $j) {
            $seen_left = false;
            $seen_right = false;

            foreach (array_keys($lines) as $i) {
                $value = $lines[$i][$j];

                if ($r != $regions[$i][$j]) {
                    continue;
                }

                if ((!$seen_left) && ((!isset($lines[$i][$j - 1]) || ($regions[$i][$j - 1] != $regions[$i][$j])))) {
                    $seen_left = true;
                    $plots[$regions[$i][$j]]["perimeter"]++;
                }
                if ((!$seen_right) && ((!isset($lines[$i][$j + 1]) || ($regions[$i][$j + 1] != $regions[$i][$j])))) {
                    $seen_right = true;
                    $plots[$regions[$i][$j]]["perimeter"]++;
                }
                if (((isset($lines[$i + 1][$j]) && ($regions[$i + 1][$j] != $regions[$i][$j])))) {
                    $seen_left = false;
                    $seen_right = false;
                }
                if ((isset($lines[$i][$j + 1]) && ($regions[$i][$j + 1] != $regions[$i][$j])) && (isset($lines[$i + 1][$j + 1]) && ($regions[$i + 1][$j + 1] == $regions[$i][$j]))) {
                    $seen_right = false;
                }
                if ((isset($lines[$i][$j - 1]) && ($regions[$i][$j - 1] != $regions[$i][$j])) && (isset($lines[$i + 1][$j - 1]) && ($regions[$i + 1][$j - 1] == $regions[$i][$j]))) {
                    $seen_left = false;
                }
            }
        }
    }

    foreach ($plots as $plot) {
        $sum += $plot["area"] * $plot["perimeter"];
    }

    return $sum;
}

$resA = partA($lines);
echo "Part A: $resA\n";

$resB = partB($lines);
echo "Part B: $resB\n";

?>
