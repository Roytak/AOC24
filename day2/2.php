<?php

function parse_line($line) {
    $line_len = count($line);
    $increasing = true;

    if ($line[0] > $line[1]) {
        $increasing = false;
    }

    for ($i = 0; $i < $line_len - 1; $i++) {
        $diff = $line[$i] - $line[$i + 1];

        if ($diff == 0 || abs($diff) > 3) {
            return false;
        }

        if (($diff < 0 && !$increasing) || ($diff > 0 && $increasing)) {
            // monotony has changed
            return false;
        }
    }

    return true;
}

function partA($input) {
    $sum = 0;

    foreach ($input as $key => $value) {
        if (parse_line($value)) {
            $sum++;
        }
    }

    return $sum;
}

function parse_line_B($line) {
    if (parse_line($line)) {
        // it was ok originally
        return true;
    }

    foreach (array_keys($line) as $key) {
        // create a new array without the current key
        $new = array_values(array_diff_key($line, [$key => $line[$key]]));
        if (parse_line($new)) {
            return true;
        }
    }

    return false;
}

function partB($input) {
    $sum = 0;

    foreach ($input as $key => $value) {
        if (parse_line_B($value)) {
            $sum++;
        }
    }

    return $sum;
}

$lines = file('in.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$input = [];

foreach ($lines as $line) {
    $input[] = explode(' ', $line);
}

$resultA = partA($input);
echo "Part A answer: $resultA\n";

$resultB = partB($input);
echo "Part B answer: $resultB\n";

?>
