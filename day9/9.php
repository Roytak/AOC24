<?php

$input = array_map('intval', str_split(trim(file_get_contents('in.txt'))));
$disk = [];
$id = 0;

foreach ($input as $k => $v) {
    if ($k % 2 == 0) {
        // appending ids
        for ($i = 0; $i < $v; $i++) {
            $disk[] = $id;
        }
        $id++;
    } else {
        for ($i = 0; $i < $v; $i++) {
            $disk[] = '.';
        }
    }
}

function partA() {
    global $disk;

    $dots = count(array_filter($disk, fn($v) => $v === '.'));

    $new_disk_space = count($disk) - $dots;

    $i = 0;
    $j = count($disk) - 1;

    while ($i < $j) {
        if ($disk[$i] != '.') {
            $i++;
            continue;
        }

        while ($disk[$j] == '.') {
            $j--;
        }

        if ($i > $j) {
            break;
        }

        $disk[$i] = $disk[$j];
        $i++;
        $j--;
    }

    $sum = 0;
    for ($k = 0; $k < $new_disk_space; $k++) {
        $sum += $k * $disk[$k];
    }

    return $sum;
}

function partB() {
    global $disk;

    $i = 0;
    $j = count($disk) - 1;
    $end = count($disk);

    while ($j >= 0) {
        $found = false;
        $space_needed = 0;
        $val = 0;

        while (!$space_needed && $j >= 0) {
            if ($disk[$j] == '.') {
                $j--;
                continue;
            }

            $val = $disk[$j];
            while ($j >= 0 && $disk[$j] == $val) {
                $space_needed++;
                $j--;
            }
        }

        if ($j < 0) {
            break;
        }

        $found_space = 0;
        $space_begin = 0;
        for ($i = 0; $i < $j; $i++) {
            $found_space = 0;

            if ($disk[$i] == '.') {
                $space_begin = $i;

                while ($disk[$i] == '.') {
                    $found_space++;
                    $i++;
                }

                if ($found_space >= $space_needed) {
                    for ($k = 0; $k < $space_needed; $k++) {
                        $disk[$space_begin + $k] = $val;
                        $disk[$j + 1 + $k] = '.';
                    }
                    break;
                }
            }
        }

    }

    $sum = 0;
    for ($i = 0; $i < $end - 1; $i++) {
        if ($disk[$i] != '.') {
            $sum += $i * $disk[$i];
        }
    }

    return $sum;
}

$resA = partA();
echo "Part A: $resA\n";

$resB = partB();
echo "Part B: $resB\n";

?>
