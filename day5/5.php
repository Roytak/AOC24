<?php

function partA($ordering, $updates) {
    $ok_updates = [];
    $failed_updates = [];
    $sum = 0;

    foreach ($updates as $up) {
        $is_ok = true;

        foreach ($up as $i => $value1) {
            foreach (array_slice($up, $i + 1) as $value2) {
                if (isset($ordering[$value2]) && in_array($value1, $ordering[$value2])) {
                    $failed_updates[] = $up;
                    $is_ok = false;
                    break 2;
                }
            }
        }

        if ($is_ok) {
            $ok_updates[] = $up;
        }
    }

    $sum = array_sum(array_map(fn($up) => $up[intval(count($up) / 2)], $ok_updates));

    return [$sum, $failed_updates];
}

function check_update($ordering, $up, &$i, &$j) {
    $len = count($up);

    for ($i = 0; $i < $len; $i++) {
        for ($j = $i + 1; $j < $len; $j++) {
            if (isset($ordering[$up[$j]]) && in_array($up[$i], $ordering[$up[$j]])) {
                return false;
            }
        }
    }

    return true;
}

function partB($ordering, $updates) {
    $final = [];

    foreach ($updates as $up) {
        $tmp = $up;
        while (!check_update($ordering, $tmp, $i, $j)) {
            [$tmp[$i], $tmp[$j]] = [$tmp[$j], $tmp[$i]];
        }
        $final[] = $tmp;
    }

    return array_sum(array_map(fn($up) => $up[intval(count($up) / 2)], $final));
}

$content = file_get_contents('in.txt');

list($ordering_in, $updates_in) = explode("\n\n", $content, 2);

$ordering_in = explode("\n", trim($ordering_in));
$updates_in = explode("\n", trim($updates_in));

foreach ($ordering_in as $or) {
    list($key, $value) = explode('|', $or);
    $ordering[$key][] = $value;
}
$updates = array_map(fn($val) => explode(',', $val), $updates_in);

[$resA, $failed_updates] = partA($ordering, $updates);
echo "Result A: $resA\n";

$resB = partB($ordering, $failed_updates);
echo "Result B: $resB\n";

?>
