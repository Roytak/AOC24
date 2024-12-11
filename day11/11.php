<?php

$input = array_map('intval', explode(' ', file_get_contents('in.txt')));
$stones = [];
foreach ($input as $s) {
	$stones[] = [$s, 1];
}

function solve($stones, $iters) {
	$sum = 0;

	for ($i = 0; $i < $iters; $i++) {
		$len = count($stones);

		for ($j = 0; $j < $len; $j++) {
			if ($stones[$j][0] == 0) {
				$stones[$j][0] = 1;
			} else {
				$str = (string)$stones[$j][0];
				$l = strlen($str);
				if ($l % 2 == 0) {
					// even, split into half
					$half = $l / 2;
					$left = substr($str, 0, $half);
					$right = substr($str, $half);

					if ($stones[$j][1] > 1) {
						$stones[$j] = [(int)$left, $stones[$j][1]];
						$stones[] = [(int)$right, $stones[$j][1]];
					} else {
						$stones[$j][0] = (int)$left;
						$stones[] = [(int)$right, 1];
					}
				} else {
					// odd, multiply by 2024
					$stones[$j][0] *= 2024;
				}
			}
		}

		// find duplicate stones
		$result = [];
		foreach ($stones as $stone) {
		    $key = $stone[0];
		    if (isset($result[$key])) {
		        $result[$key] += $stone[1];
		    } else {
		        $result[$key] = $stone[1];
		    }
		}

		$stones = [];
		foreach ($result as $key => $value) {
		    $stones[] = [$key, $value];
		}
	}

	return array_sum(array_column($stones, 1));
}

$resA = solve($stones, 25);
echo "Part A: $resA\n";

$resB = solve($stones, 75);
echo "Part B: $resB\n";

?>
