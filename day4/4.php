<?php

function check_horizontal($line, $i) {
	$sum = 0;

	if (strpos($line, "XMAS", $i) === $i) {
		$sum++;
	}

	if ($i - 3 >= 0 && strpos($line, "SAMX", $i - 3) === $i - 3) {
		$sum++;
	}

	return $sum;
}

function check_vertical($array, $i, $j) {
	$sum = 0;

	if ($i + 3 < count($array)) {
		if ($array[$i][$j] === "X" && $array[$i + 1][$j] === "M" && $array[$i + 2][$j] === "A" && $array[$i + 3][$j] === "S") {
			$sum++;
		}
	}

	if ($i - 3 >= 0) {
		if ($array[$i][$j] === "X" && $array[$i - 1][$j] === "M" && $array[$i - 2][$j] === "A" && $array[$i - 3][$j] === "S") {
			$sum++;
		}
	}

	return $sum;
}

function check_diagonal($array, $i, $j) {
	$sum = 0;

	// top right
	if ($i - 3 >= 0 && $j + 3 < strlen($array[$i])) {
		if ($array[$i][$j] === "X" && $array[$i - 1][$j + 1] === "M" && $array[$i - 2][$j + 2] === "A" && $array[$i - 3][$j + 3] === "S") {
			$sum++;
		}
	}

	// top left
	if ($i - 3 >= 0 && $j - 3 >= 0) {
		if ($array[$i][$j] === "X" && $array[$i - 1][$j - 1] === "M" && $array[$i - 2][$j - 2] === "A" && $array[$i - 3][$j - 3] === "S") {
			$sum++;
		}
	}

	// bottom right
	if ($i + 3 < count($array) && $j + 3 < strlen($array[$i])) {
		if ($array[$i][$j] === "X" && $array[$i + 1][$j + 1] === "M" && $array[$i + 2][$j + 2] === "A" && $array[$i + 3][$j + 3] === "S") {
			$sum++;
		}
	}

	// bottom left
	if ($i + 3 < count($array) && $j - 3 >= 0) {
		if ($array[$i][$j] === "X" && $array[$i + 1][$j - 1] === "M" && $array[$i + 2][$j - 2] === "A" && $array[$i + 3][$j - 3] === "S") {
			$sum++;
		}
	}

	return $sum;
}

function check_mas($array, $i, $j) {
	$sum = 0;

	// left to right diagonal
	if ($j - 1 >= 0 && $j + 1 < strlen($array[$i]) && $i - 1 >= 0 && $i + 1 < count($array)) {
		if (($array[$i - 1][$j - 1] === "M" && $array[$i + 1][$j + 1] === "S") || ($array[$i - 1][$j - 1] === "S" && $array[$i + 1][$j + 1] === "M")) {
			if (($array[$i - 1][$j + 1] === "M" && $array[$i + 1][$j - 1] === "S") || ($array[$i - 1][$j + 1] === "S" && $array[$i + 1][$j - 1] === "M")) {
				$sum++;
			}
		}
	}

	return $sum;
}

function partA($input) {
	$sum = 0;

	foreach ($input as $i => $line) {
		$chars = str_split($line);
		foreach ($chars as $j => $c) {
			if ($c === "X") {
				$sum += check_horizontal($line, $j);
				$sum += check_vertical($input, $i, $j);
				$sum += check_diagonal($input, $i, $j);
			}
		}
	}

	return $sum;
}

function partB($input) {
	$sum = 0;

	foreach ($input as $i => $line) {
		$chars = str_split($line);
		foreach ($chars as $j => $c) {
			if ($c === "A") {
				$sum += check_mas($input, $i, $j);
			}
		}
	}

	return $sum;
}

$lines = file("in.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$answerA = partA($lines);
echo "Answer A: $answerA\n";

$answerB = partB($lines);
echo "Answer B: $answerB\n";

?>
