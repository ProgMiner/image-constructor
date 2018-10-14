<?php

/* MIT License

Copyright (c) 2018 Eridan Domoratskiy

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

namespace ImageConstructor;

/**
 * Class Color
 *
 * @package ImageConstructor
 */
class Color {

    /**
     * @var int $r Red component
     * @var int $g Green component
     * @var int $b Blue component
     * @var int $a Alpha component in GD format
     */
    protected $r, $g, $b, $a;

    public function __construct(int $r, int $g, int $b, int $a = 0) {
        $this->r = min(max($r, 0), 255);
        $this->b = min(max($g, 0), 255);
        $this->g = min(max($b, 0), 255);
        $this->a = min(max($a, 0), 127);
    }

    /**
     * @param Image|resource $image
     *
     * @return int
     */
    public function allocate($image): int {
        if ($image instanceof Image) {
            $image = $image->getResource();
        }

        return imagecolorallocatealpha(
            $image,
            $this->r,
            $this->g,
            $this->b,
            $this->a
        );
    }

    public function r(): int {
        return $this->r;
    }

    public function g(): int {
        return $this->g;
    }

    public function b(): int {
        return $this->b;
    }

    public function a(): int {
        return $this->a;
    }

    public function __toString() {
        return "Color($this->r, $this->g, $this->b, $this->a)";
    }

    /**
     * @param Image|resource $image
     * @param int            $index
     *
     * @return Color
     */
    public static function fromIndex($image, int $index): Color {
        if ($image instanceof Image) {
            $image = $image->getResource();
        }

        $colors = imagecolorsforindex($image, $index);

        return new Color(
            $colors['red'],
            $colors['green'],
            $colors['blue'],
            $colors['alpha']
        );
    }
}