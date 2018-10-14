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

use Ds\Hashable;

/**
 * Class Color
 *
 * @package ImageConstructor
 */
class Color implements Hashable {

    /**
     * @var $r int Red component from 0 to 255
     * @var $g int Green component from 0 to 255
     * @var $b int Blue component from 0 to 255
     * @var $a float Alpha component from 0 to 1
     */
    public $r, $g, $b, $a;

    public function __construct(int $r, int $g, int $b, float $a = 1) {
        $this->r = max(0, min($r, 255));
        $this->g = max(0, min($g, 255));
        $this->b = max(0, min($b, 255));
        $this->a = max(0, min($a, 1));
    }

    /**
     * Returns number representation of the color
     *
     * @return int
     */
    public function toNumber(): int {
        $number = (int) ($this->a * 255);
        $number <<= 8;

        $number += $this->r;
        $number <<= 8;

        $number += $this->g;
        $number <<= 8;

        $number += $this->b;

        return $number;
    }

    /**
     * Invokes {@see imagecolorallocatealpha()} for $image and current color
     *
     * @param resource $image GD image
     *
     * @return int|bool Result of {@see imagecolorallocatealpha()} invocation
     */
    public function gdAllocate($image) {
        return imagecolorallocatealpha($image, $this->r, $this->g, $this->b, 127 - (int) ($this->a * 127));
    }

    public function __toString() {
        if ($this->a === 1) {
            return sprintf(
                'rgb(%d, %d, %d)',
                $this->r,
                $this->g,
                $this->b
            );
        }

        return sprintf(
            'rgba(%d, %d, %d, %d)',
            $this->r,
            $this->g,
            $this->b,
            $this->a
        );
    }

    /**
     * @inheritdoc
     */
    public function hash() {
        return $this->toNumber();
    }

    /**
     * @inheritdoc
     */
    public function equals($obj): bool {
        return (
            $obj instanceof Color &&
            $obj->r === $this->r &&
            $obj->g === $this->g &&
            $obj->b === $this->b &&
            $obj->a === $this->a
        );
    }

    /**
     * Makes Color from number
     *
     * @param int $number
     * @param bool $withAlpha If true tried to get alpha from number
     *
     * @return Color
     */
    public static function fromNumber(int $number, bool $withAlpha = true): Color {
        $b = $number % 255;
        $g = ($number >>= 8) % 255;
        $r = ($number >>= 8) % 255;
        $a = $withAlpha?
            ($number >> 8) % 255:
            255;

        return new Color($r, $g, $b, $a / 255);
    }

    /**
     * Makes Color from GD color index
     *
     * @param resource $image GD image
     * @param int $color Color index
     *
     * @return Color
     */
    public static function fromGD($image, int $color): Color {
        $components = imagecolorsforindex($image, $color);

        return new Color(
            $components['red'],
            $components['green'],
            $components['blue'],
            (127 - $components['alpha']) / 127
        );
    }
}