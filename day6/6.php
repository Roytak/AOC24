<?php

function partA($map, $available_pos, $guard, $map_width, $map_height) {
    $dir = 'u';

    while (1) {
    $added = 0;

    //echo "Guard: ($guard[0],$guard[1]), going $dir\n";
    if ($dir == 'u' || $dir == 'd') {
        $same_col = array_filter($available_pos, function ($pos) use ($guard,$map,$map_height) {
            return $pos[1] === $guard[1] && $pos[0] != $guard[0] && (($pos[0] - 1 >= 0 && $map[$pos[0] - 1][$pos[1]] == '#') || ($pos[0] + 1 < $map_height && $map[$pos[0] + 1][$pos[1]] == '#'));
        });

        if ($dir == 'u') {
            $next_y = array_reduce($same_col, function ($carry, $pos) use ($guard) {
                if ($pos[0] <= $guard[0] && ($carry === null || $pos[0] > $carry)) {
                    return $pos[0];
                }
                return $carry;
            }, null);

            $dir = 'r';

            $n = $next_y ? $next_y : 0;
            for ($i = $guard[0]; $i >= $n; $i--) {
                if (!isset($visited["$i,$guard[1]"])) {
                    $visited["$i,$guard[1]"] = 1;
                    $added++;
                }
            }
            if (!$added) {
                break;
            }
        } else {
            $next_y = array_reduce($same_col, function ($carry, $pos) use ($guard) {
                if ($pos[0] >= $guard[0] && ($carry === null || $pos[0] < $carry)) {
                    return $pos[0];
                }
                return $carry;
            }, null);

            $dir = 'l';

            $n = $next_y ? $next_y : $map_height - 1;
            for ($i = $guard[0]; $i <= $n; $i++) {
                if (!isset($visited["$i,$guard[1]"])) {
                    $visited["$i,$guard[1]"] = 1;
                    $added++;
                }
            }
            if (!$added) {
                break;
            }
        }

        if (!$next_y) {
            break;
        }

        $guard = [$next_y, $guard[1]];
    } else {
        $same_row = array_filter($available_pos, function ($pos) use ($guard,$map,$map_width) {
            return $pos[0] === $guard[0] && $pos[1] != $guard[1] && (($pos[1] - 1 >= 0 && $map[$pos[0]][$pos[1] - 1] == '#') || ($pos[1] + 1 < $map_width && $map[$pos[0]][$pos[1] + 1] == '#'));
        });

        if ($dir == 'l') {
            $next_x = array_reduce($same_row, function ($carry, $pos) use ($guard) {
                if ($pos[1] <= $guard[1] && ($carry === null || $pos[1] > $carry)) {
                    return $pos[1];
                }
                return $carry;
            }, null);

            $dir = 'u';

            $n = $next_x ? $next_x : 0;
            for ($i = $guard[1]; $i >= $n; $i--) {
                if (!isset($visited["$guard[0],$i"])) {
                    $visited["$guard[0],$i"] = 1;
                    $added++;
                }
            }
            if (!$added) {
                break;
            }
        } else {
            $next_x = array_reduce($same_row, function ($carry, $pos) use ($guard) {
                if ($pos[1] >= $guard[1] && ($carry === null || $pos[1] < $carry)) {
                    return $pos[1];
                }
                return $carry;
            }, null);

            $dir = 'd';

            $n = $next_x ? $next_x : $map_width - 1;
            for ($i = $guard[1]; $i <= $n; $i++) {
                if (!isset($visited["$guard[0],$i"])) {
                    $visited["$guard[0],$i"] = 1;
                    $added++;
                }
            }
            if (!$added) {
                break;
            }
        }

        if (!$next_x) {
            break;
        }

        $guard = [$guard[0], $next_x];
    }
}

return count($visited);
}

function partB($map, $available_pos, $guard, $map_width, $map_height) {
    $dir = 'u';
    $seen = [];

    while (1) {
    $added = 0;

    //echo "Guard: ($guard[0],$guard[1]), going $dir\n";
    if ($dir == 'u' || $dir == 'd') {
        $same_col = array_filter($available_pos, function ($pos) use ($guard,$map,$map_height,$dir) {
            if ($dir == 'u') {
                return $pos[1] === $guard[1] && ($pos[0] - 1 >= 0) && ($map[$pos[0] - 1][$pos[1]] == '#');
            } else {
                return $pos[1] === $guard[1] && ($pos[0] + 1 < $map_height) && ($map[$pos[0] + 1][$pos[1]] == '#');
            }
        });

        if ($dir == 'u') {
            $next_y = array_reduce($same_col, function ($carry, $pos) use ($guard) {
                if ($pos[0] <= $guard[0] && ($carry === null || $pos[0] > $carry)) {
                    return $pos[0];
                }
                return $carry;
            }, null);

            $dir = 'r';
        } else {
            $next_y = array_reduce($same_col, function ($carry, $pos) use ($guard) {
                if ($pos[0] >= $guard[0] && ($carry === null || $pos[0] < $carry)) {
                    return $pos[0];
                }
                return $carry;
            }, null);

            $dir = 'l';
        }

        if (!$next_y) {
            break;
        }

        $guard = [$next_y, $guard[1]];
    } else {
        $same_row = array_filter($available_pos, function ($pos) use ($guard,$map,$map_width,$dir) {
            if ($dir == 'l') {
                return $pos[0] === $guard[0] && ($pos[1] - 1 >= 0) && ($map[$pos[0]][$pos[1] - 1] == '#');
            } else {
                return $pos[0] === $guard[0] && ($pos[1] + 1 < $map_width) && ($map[$pos[0]][$pos[1] + 1] == '#');
            }
        });

        if ($dir == 'l') {
            $next_x = array_reduce($same_row, function ($carry, $pos) use ($guard) {
                if ($pos[1] <= $guard[1] && ($carry === null || $pos[1] > $carry)) {
                    return $pos[1];
                }
                return $carry;
            }, null);

            $dir = 'u';
        } else {
            $next_x = array_reduce($same_row, function ($carry, $pos) use ($guard) {
                if ($pos[1] >= $guard[1] && ($carry === null || $pos[1] < $carry)) {
                    return $pos[1];
                }
                return $carry;
            }, null);

            $dir = 'd';
        }

        if (!$next_x) {
            break;
        }

        $guard = [$guard[0], $next_x];
    }

    if (isset($seen["$guard[0],$guard[1]"]) && in_array($dir, $seen["$guard[0],$guard[1]"])) {
        return true;
    }

    $seen["$guard[0],$guard[1]"][] = $dir;
}

return false;
}

function get_avail_pos($map, $map_width, $map_height) {
    $available_pos = [];

    foreach ($map as $i => $row) {
        foreach ($row as $j => $column) {
            if ($map[$i][$j] == '#') {
                if ($i > 0) {
                    $available_pos[($i - 1) . ",$j"] = [$i - 1, $j];
                }
                if ($i < $map_height - 1) {
                    $available_pos[($i + 1) . ",$j"] = [$i + 1, $j];
                }
                if ($j > 0) {
                    $available_pos["$i," . ($j - 1)] = [$i, $j - 1];
                }
                if ($j < $map_width - 1) {
                    $available_pos["$i," . ($j + 1)] = [$i, $j + 1];
                }
            }
        }
    }

    $available_pos = array_values($available_pos);

    return $available_pos;
}

$map = array_map('str_split', file("in.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
$map_width = count($map[0]);
$map_height = count($map);

$available_pos = [];

foreach ($map as $i => $row) {
    foreach ($row as $j => $column) {
        if ($map[$i][$j] == '#') {
            if ($i > 0) {
                $available_pos[($i - 1) . ",$j"] = [$i - 1, $j];
            }
            if ($i < $map_height - 1) {
                $available_pos[($i + 1) . ",$j"] = [$i + 1, $j];
            }
            if ($j > 0) {
                $available_pos["$i," . ($j - 1)] = [$i, $j - 1];
            }
            if ($j < $map_width - 1) {
                $available_pos["$i," . ($j + 1)] = [$i, $j + 1];
            }
        }
        if ($map[$i][$j] == '^') {
            $guard = [$i, $j];
        }
    }
}

$available_pos = array_values($available_pos);
$visited = [];

$dir = 'u';

$partA = partA($map, $available_pos, $guard, $map_width, $map_height);

echo "Part A: $partA\n";

$cnt = 0;

foreach ($map as $i => $row) {
    foreach ($row as $j => $col) {
        if ($map[$i][$j] == '#' || $map[$i][$j] == '^') {
            continue;
        }

        $map[$i][$j] = '#';
        $available_pos = get_avail_pos($map, $map_width, $map_height);
        if (partB($map, $available_pos, $guard, $map_width, $map_height)) {
            $cnt++;
        }
        $map[$i][$j] = '.';
    }
    echo "$i ";
}

echo "Part B: $cnt\n";

?>
