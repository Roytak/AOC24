<?php

$input = file_get_contents('in.txt');

$in = explode("\n\n", $input);

$lines = explode("\n", $in[0]);

$map = array_map('str_split', $lines);
$moves = $in[1];

function find_robot($map) {
    foreach ($map as $i => $row) {
        foreach ($row as $j => $v) {
            if ($v == '@') {
                return [$i, $j];
            }
        }
    }
}

function partA() {
    global $map;
    global $moves;

    $map_width = count($map[0]);
    $map_height = count($map);

    [$rX, $rY] = find_robot($map);

    $moves = str_split($moves);

    foreach ($moves as $move) {
        if ($move === '<') {
            if ($map[$rY][$rX - 1] === '.') {
                $map[$rY][$rX - 1] = '@';
                $map[$rY][$rX] = '.';
                $rX -= 1;
            } elseif ($map[$rY][$rX - 1] === 'O') {
                for ($i = $rX - 1; $i >= 0; $i--) {
                    if ($map[$rY][$i] === '.') {
                        for ($j = $i; $j < $rX - 1; $j++) {
                            $map[$rY][$j] = 'O';
                        }
                        $map[$rY][$rX - 1] = '@';
                        $map[$rY][$rX] = '.';
                        $rX -= 1;
                        break;
                    } elseif ($map[$rY][$i] === '#') {
                        break;
                    }
                }
            }
        } elseif ($move === '>') {
            if ($map[$rY][$rX + 1] === '.') {
                $map[$rY][$rX + 1] = '@';
                $map[$rY][$rX] = '.';
                $rX += 1;
            } elseif ($map[$rY][$rX + 1] === 'O') {
                for ($i = $rX + 1; $i < $map_width; $i++) {
                    if ($map[$rY][$i] === '.') {
                        for ($j = $i; $j > $rX + 1; $j--) {
                            $map[$rY][$j] = 'O';
                        }
                        $map[$rY][$rX + 1] = '@';
                        $map[$rY][$rX] = '.';
                        $rX += 1;
                        break;
                    } elseif ($map[$rY][$i] === '#') {
                        break;
                    }
                }
            }
        } elseif ($move === '^') {
            if ($map[$rY - 1][$rX] === '.') {
                $map[$rY - 1][$rX] = '@';
                $map[$rY][$rX] = '.';
                $rY -= 1;
            } elseif ($map[$rY - 1][$rX] === 'O') {
                for ($i = $rY - 1; $i >= 0; $i--) {
                    if ($map[$i][$rX] === '.') {
                        for ($j = $i; $j < $rY - 1; $j++) {
                            $map[$j][$rX] = 'O';
                        }
                        $map[$rY - 1][$rX] = '@';
                        $map[$rY][$rX] = '.';
                        $rY -= 1;
                        break;
                    } elseif ($map[$i][$rX] === '#') {
                        break;
                    }
                }
            }
        } elseif ($move === 'v') {
            if ($map[$rY + 1][$rX] === '.') {
                $map[$rY + 1][$rX] = '@';
                $map[$rY][$rX] = '.';
                $rY += 1;
            } elseif ($map[$rY + 1][$rX] === 'O') {
                for ($i = $rY + 1; $i < $map_height; $i++) {
                    if ($map[$i][$rX] === '.') {
                        for ($j = $i; $j > $rY + 1; $j--) {
                            $map[$j][$rX] = 'O';
                        }
                        $map[$rY + 1][$rX] = '@';
                        $map[$rY][$rX] = '.';
                        $rY += 1;
                        break;
                    } elseif ($map[$i][$rX] === '#') {
                        break;
                    }
                }
            }
        }
    }

    $sum = 0;

    foreach ($map as $i => $row) {
        foreach ($row as $j => $value) {
            if ($value === 'O') {
                $sum += 100 * $i + $j;
            }
            #echo "$value ";
        }
        #echo "\n";
    }

    return $sum;
}

function resize_map($old_map) {
    $map = [];

    foreach ($old_map as $row) {
        $new_row = [];

        foreach ($row as $v) {
            if ($v === '#') {
                $new_row[] = '#';
                $new_row[] = '#';
            } elseif ($v === '.') {
                $new_row[] = '.';
                $new_row[] = '.';
            } elseif ($v === 'O') {
                $new_row[] = '[';
                $new_row[] = ']';
            } elseif ($v === '@') {
                $new_row[] = '@';
                $new_row[] = '.';
            }
        }

        $map[] = $new_row;
    }

    return $map;
}

class Box {
    public $map;
    public $y;
    public $lx;
    public $rx;

    function __construct(&$map, $y, $x) {
        $this->map = &$map;
        $this->y = $y;
        $this->lx = $x;
        $this->rx = $x + 1;
    }

    function move_left() {
        if ($this->map[$this->y][$this->lx - 1] === '#') {
            return false;
        } elseif ($this->map[$this->y][$this->lx - 1] === '.') {
            $this->map[$this->y][$this->lx - 1] = '[';
            $this->map[$this->y][$this->lx] = ']';
            $this->map[$this->y][$this->rx] = '.';
            return true;
        } elseif ($this->map[$this->y][$this->lx - 1] === ']') {
            $new_box = new Box($this->map, $this->y, $this->lx - 2);
            if ($new_box->move_left()) {
                $this->map[$this->y][$this->lx - 1] = '[';
                $this->map[$this->y][$this->lx] = ']';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        }
    }

    function move_right() {
        if ($this->map[$this->y][$this->rx + 1] === '#') {
            return false;
        } elseif ($this->map[$this->y][$this->rx + 1] === '.') {
            $this->map[$this->y][$this->lx + 1] = '[';
            $this->map[$this->y][$this->rx + 1] = ']';
            $this->map[$this->y][$this->lx] = '.';
            return true;
        } elseif ($this->map[$this->y][$this->rx + 1] === '[') {
            $new_box = new Box($this->map, $this->y, $this->rx + 1);
            if ($new_box->move_right()) {
                $this->map[$this->y][$this->lx + 1] = '[';
                $this->map[$this->y][$this->rx + 1] = ']';
                $this->map[$this->y][$this->lx] = '.';
                return true;
            } else {
                return false;
            }
        }
    }

    function move_up() {
        if (($this->map[$this->y - 1][$this->lx] === '#') || ($this->map[$this->y - 1][$this->rx] === '#')) {
            return false;
        } elseif (($this->map[$this->y - 1][$this->lx] === '.') && ($this->map[$this->y - 1][$this->rx] === '.')) {
            $this->map[$this->y - 1][$this->lx] = '[';
            $this->map[$this->y - 1][$this->rx] = ']';
            $this->map[$this->y][$this->lx] = '.';
            $this->map[$this->y][$this->rx] = '.';
            return true;
        } elseif (($this->map[$this->y - 1][$this->lx] === ']') && ($this->map[$this->y - 1][$this->rx] === '.')) {
            $new_box = new Box($this->map, $this->y - 1, $this->lx - 1);
            if ($new_box->move_up()) {
                $this->map[$this->y - 1][$this->lx] = '[';
                $this->map[$this->y - 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif ($this->map[$this->y - 1][$this->lx] === '[') {
            $new_box = new Box($this->map, $this->y - 1, $this->lx);
            if ($new_box->move_up()) {
                $this->map[$this->y - 1][$this->lx] = '[';
                $this->map[$this->y - 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif (($this->map[$this->y - 1][$this->rx] === '[') && ($this->map[$this->y - 1][$this->lx] === '.')) {
            $new_box = new Box($this->map, $this->y - 1, $this->rx);
            if ($new_box->move_up()) {
                $this->map[$this->y - 1][$this->lx] = '[';
                $this->map[$this->y - 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif (($this->map[$this->y - 1][$this->lx] === ']') && ($this->map[$this->y - 1][$this->rx] === '[')) {
            $copy = array_replace([], $this->map);

            $l_box = new Box($this->map, $this->y - 1, $this->lx - 1);
            $r_box = new Box($this->map, $this->y - 1, $this->rx);
            if ($l_box->move_up() && $r_box->move_up()) {
                $this->map[$this->y - 1][$this->lx] = '[';
                $this->map[$this->y - 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                $this->map = $copy;
                return false;
            }
        }
    }

    function move_down() {
        if (($this->map[$this->y + 1][$this->lx] === '#') || ($this->map[$this->y + 1][$this->rx] === '#')) {
            return false;
        } elseif (($this->map[$this->y + 1][$this->lx] === '.') && ($this->map[$this->y + 1][$this->rx] === '.')) {
            $this->map[$this->y + 1][$this->lx] = '[';
            $this->map[$this->y + 1][$this->rx] = ']';
            $this->map[$this->y][$this->lx] = '.';
            $this->map[$this->y][$this->rx] = '.';
            return true;
        } elseif (($this->map[$this->y + 1][$this->lx] === ']') && ($this->map[$this->y + 1][$this->rx] === '.')) {
            $new_box = new Box($this->map, $this->y + 1, $this->lx - 1);
            if ($new_box->move_down()) {
                $this->map[$this->y + 1][$this->lx] = '[';
                $this->map[$this->y + 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif ($this->map[$this->y + 1][$this->lx] === '[') {
            $new_box = new Box($this->map, $this->y + 1, $this->lx);
            if ($new_box->move_down()) {
                $this->map[$this->y + 1][$this->lx] = '[';
                $this->map[$this->y + 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif (($this->map[$this->y + 1][$this->rx] === '[') && ($this->map[$this->y + 1][$this->lx] === '.')) {
            $new_box = new Box($this->map, $this->y + 1, $this->rx);
            if ($new_box->move_down()) {
                $this->map[$this->y + 1][$this->lx] = '[';
                $this->map[$this->y + 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                return false;
            }
        } elseif (($this->map[$this->y + 1][$this->lx] === ']') && ($this->map[$this->y + 1][$this->rx] === '[')) {
            $copy = array_replace([], $this->map);

            $l_box = new Box($this->map, $this->y + 1, $this->lx - 1);
            $r_box = new Box($this->map, $this->y + 1, $this->rx);
            if ($l_box->move_down() && $r_box->move_down()) {
                $this->map[$this->y + 1][$this->lx] = '[';
                $this->map[$this->y + 1][$this->rx] = ']';
                $this->map[$this->y][$this->lx] = '.';
                $this->map[$this->y][$this->rx] = '.';
                return true;
            } else {
                $this->map = $copy;
                return false;
            }
        }
    }
}

function partB($old_map, $moves) {

    $moves = str_split($moves);

    $map = resize_map($old_map);

    [$rY, $rX] = find_robot($map);

    foreach ($moves as $move) {
        if ($move === '<') {
            if ($map[$rY][$rX - 1] === '.') {
                $map[$rY][$rX - 1] = '@';
                $map[$rY][$rX] = '.';
                $rX -= 1;
            } elseif ($map[$rY][$rX - 1] === ']') {
                $box = new Box($map, $rY, $rX - 2);
                if ($box->move_left()) {
                    $map[$rY][$rX - 1] = '@';
                    $map[$rY][$rX] = '.';
                    $rX -= 1;
                }
            }
        } elseif ($move === '>') {
            if ($map[$rY][$rX + 1] === '.') {
                $map[$rY][$rX + 1] = '@';
                $map[$rY][$rX] = '.';
                $rX += 1;
            } elseif ($map[$rY][$rX + 1] === '[') {
                $box = new Box($map, $rY, $rX + 1);
                if ($box->move_right()) {
                    $map[$rY][$rX + 1] = '@';
                    $map[$rY][$rX] = '.';
                    $rX += 1;
                }
            }
        } elseif ($move === '^') {
            if ($map[$rY - 1][$rX] === '.') {
                $map[$rY - 1][$rX] = '@';
                $map[$rY][$rX] = '.';
                $rY -= 1;
            } elseif (($map[$rY - 1][$rX] === ']') || ($map[$rY - 1][$rX] === '[')) {
                if ($map[$rY - 1][$rX] === ']') {
                    $box = new Box($map, $rY - 1, $rX - 1);
                } else {
                    $box = new Box($map, $rY - 1, $rX);
                }
                if ($box->move_up()) {
                    $map[$rY - 1][$rX] = '@';
                    $map[$rY][$rX] = '.';
                    $rY -= 1;
                }
            }
        } elseif ($move === 'v') {
            if ($map[$rY + 1][$rX] === '.') {
                $map[$rY + 1][$rX] = '@';
                $map[$rY][$rX] = '.';
                $rY += 1;
            } elseif (($map[$rY + 1][$rX] === ']') || ($map[$rY + 1][$rX] === '[')) {
                if ($map[$rY + 1][$rX] === ']') {
                    $box = new Box($map, $rY + 1, $rX - 1);
                } else {
                    $box = new Box($map, $rY + 1, $rX);
                }
                if ($box->move_down()) {
                    $map[$rY + 1][$rX] = '@';
                    $map[$rY][$rX] = '.';
                    $rY += 1;
                }
            }
        }
    }

    $sum = 0;

    foreach ($map as $i => $row) {
        foreach ($row as $j => $value) {
            if ($value === '[') {
                $sum += 100 * $i + $j;
            }
        }
    }

    return $sum;
}

$resA = partA();
echo "Part A: $resA\n";

$map = array_map('str_split', $lines);
$moves = $in[1];

$resB = partB($map, $moves);
echo "Part B: $resB\n";

?>
