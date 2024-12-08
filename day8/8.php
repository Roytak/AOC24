<?php

$map = array_map('str_split', file("in.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
$map_height = count($map);
$map_width = count($map[0]);

function get_vectors($start_row, $start_col, $symbol) {
    global $map, $map_height, $map_width;
    $vectors = [];

    for ($i = $start_row; $i < $map_height; $i++) {
        for ($j = 0; $j < $map_width; $j++) {
            if ($map[$i][$j] == $symbol && $i != $start_row && $j != $start_col) {
                $vectors["$start_row,$start_col"][] = [$i, $j];
            }
        }
    }

    return $vectors;
}

function partA() {
    global $map;

    $vectors = [];
    $antinodes = [];

    foreach ($map as $i => $row) {
        foreach ($row as $j => $v) {
            if ($v != '.') {
                $tmp = get_vectors($i, $j, $v);
                $vectors += $tmp;
            }
        }
    }

    foreach ($vectors as $key => $vec) {
        [$vecx, $vecy] = explode(',', $key);

        foreach ($vec as [$vx, $vy]) {
            [$newx, $newy] = [$vecx - $vx, $vecy - $vy];

            foreach ([1, -2] as $multiplier) {
                $ax = $vecx + $multiplier * $newx;
                $ay = $vecy + $multiplier * $newy;

                if (isset($map[$ax][$ay])) {
                    $antinodes["$ax,$ay"] = true;
                }
            }
        }
    }

    return count($antinodes);
}

function partB() {
    global $map;

    $vectors = [];
    $antennas = [];

    foreach ($map as $i => $row) {
        foreach ($row as $j => $v) {
            if ($v != '.') {
                $tmp = get_vectors($i, $j, $v);
                $vectors += $tmp;
            }
        }
    }

    foreach ($vectors as $key => $vec) {
        [$vecx, $vecy] = explode(',', $key);
        $antennas["$vecx,$vecy"] = 1;

        foreach ($vec as $v) {
            $new = [$vecx - $v[0], $vecy - $v[1]];
            $tmp = $new;
            $tmpx = $vecx;
            $tmpy = $vecy;

            while (isset($map[$tmpx + $new[0]][$tmpy + $new[1]])) {
                $a = $tmpx + $new[0];
                $b = $tmpy + $new[1];
                $antennas["$a,$b"] = 1;
                $tmpx = $a;
                $tmpy = $b;
            }

            $tmpx = $vecx;
            $tmpy = $vecy;
            while (isset($map[$tmpx - $new[0]][$tmpy - $new[1]])) {
                $a = $tmpx - $new[0];
                $b = $tmpy - $new[1];
                $antennas["$a,$b"] = 1;
                $tmpx = $a;
                $tmpy = $b;
            }
        }
    }

    return count($antennas);
}

$resA = partA();
echo "Part A: $resA\n";

$resB = partB();
echo "Part B: $resB\n";

?>
