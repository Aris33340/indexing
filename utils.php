<?php
// utils.php

function normalize($value, $min, $max, $inverse = false) {
    if ($max == $min) return 50; // Menghindari pembagian 0
    $norm = ($value - $min) / ($max - $min) * 100;
    return $inverse ? (100 - $norm) : $norm;
}
