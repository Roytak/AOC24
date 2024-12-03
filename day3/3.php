<?php

function partA($input) {
    $sum = 0;

    preg_match_all("/mul\((\d+),(\d+)\)/", $input, $matches);

    foreach ($matches[1] as $i => $v) {
        $sum += $matches[1][$i] * $matches[2][$i];
    }

    return $sum;
}

function partB($input) {
    $enabled = true;
    $offset = 0;
    $sum = 0;

    while (preg_match("/(mul\((\d+),(\d+)\)|don't\(\)|do\(\))/", $input, $match, PREG_OFFSET_CAPTURE, $offset)) {
        $match_text = $match[0][0];
        $match_pos = $match[0][1];

        if ($match_text === "don't()") {
            $enabled = false;
        } elseif ($match_text === "do()") {
            $enabled = true;
        } elseif ($enabled) {
            $sum += $match[2][0] * $match[3][0];
        }

        $offset = $match_pos + strlen($match_text);
    }

    return $sum;
}

$lines = file('in.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$text = implode('', $lines);

$resA = partA($text);
echo "Part A: $resA\n";

$resB = partB($text);
echo "Part B: $resB\n";

?>
