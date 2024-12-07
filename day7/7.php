<?php

function calcA($op, $res, $operators, $idx) {
    $eq = "";

    for ($i = 0; $i < count($operators); $i++) {
        $eq .= "(";
    }

    for ($i = 0; $i < count($operators); $i++) {
        if ($i) {
            $eq = $eq . $op[$i] . ")";
        } else {
            $eq = $eq . $op[$i];
        }
        $eq = $eq . $operators[$i];
    }
    $eq = $eq . $op[$i] . ")";


    $result = eval("return " . $eq . ";");
    if ($result == $res) {
        return $res;
    }

    if ($idx == -1) {
        return 0;
    }

    $a = calcA($op, $res, $operators, $idx - 1);
    if ($a) {
        return $a;
    }

    $operators[$idx] = '*';
    $a = calcA($op, $res, $operators, $idx - 1);
    if ($a) {
        return $a;
    }

    return 0;
}

function partA($operands, $results) {
    $sum = 0;

    foreach ($operands as $i => $v) {
        $operators = [];

        for ($j = 0; $j < count($v) - 1; $j++) {
            $operators[] = '+';
        }

        $sum += calcA($operands[$i], $results[$i], $operators, count($operators) - 1);
    }

    return $sum;
}

function calcB($op, $res, $operators, $idx) {
    $eq = "";
    $open = 0;
    $close = 0;

    for ($i = 0; $i < count($operators); $i++) {
        if ($operators[$i] != '') {
            $eq .= "(";
            $open++;
        }
    }

    for ($i = 0; $i < count($operators); $i++) {
        if ($operators[$i] == '.' || ($i && $operators[$i - 1] == '.')) {
            $eq .= "\"";
        }

        $eq = $eq . $op[$i];

        if ($operators[$i] == '.' || ($i && $operators[$i - 1] == '.')) {
            $eq .= "\"";
        }

        if ($i) {
            $eq .= ")";
            $close++;
        }

        $eq = $eq . $operators[$i];
    }

    if ($operators[$i - 1] == '.') {
        $eq = $eq . "\"" . $op[$i] . "\"" . ")";
    } else {
        $eq = $eq . $op[$i] . ")";
    }

    while ($open <= $close) {
        $eq = "(" . $eq;
        $open++;
    }

    $result = eval("return " . $eq . ";");
    if ($result == $res) {
        echo "$res OK\n";
        return $res;
    }

    if ($idx == -1) {
        return 0;
    }

    $a = calcB($op, $res, $operators, $idx - 1);
    if ($a) {
        return $a;
    }

    $operators[$idx] = '*';
    $a = calcB($op, $res, $operators, $idx - 1);
    if ($a) {
        return $a;
    }

    $operators[$idx] = '.';
    $a = calcB($op, $res, $operators, $idx - 1);
    if ($a) {
        return $a;
    }

    return 0;
}

function partB($operands, $results) {
    $sum = 0;

    foreach ($operands as $i => $v) {
        $operators = [];

        for ($j = 0; $j < count($v) - 1; $j++) {
            $operators[] = '+';
        }

        $sum += calcB($operands[$i], $results[$i], $operators, count($operators) - 1);
        echo "Line $i done \n";
    }

    return $sum;
}

$input = file('in.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($input as $i) {
    $e = explode(':', $i);
    $results[] = $e[0];
    $operands[] = explode(' ', trim($e[1]));
}

$resA = partA($operands, $results);
echo "Result A: $resA\n";

$resB = partB($operands, $results);
echo "Result B: $resB\n";

?>
