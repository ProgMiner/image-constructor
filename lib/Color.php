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
     * @var $r float Red component from 0 to 1
     * @var $g float Green component from 0 to 1
     * @var $b float Blue component from 0 to 1
     * @var $a float Alpha component from 0 to 1
     */
    public $r, $g, $b, $a;

    public function __construct(float $r, float $g, float $b, float $a = 1) {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
        $this->a = $a;
    }

    public function __toString() {
        if ($this->a === 1) {
            return sprintf(
                'rgb(%d, %d, %d)',
                $this->r * 255,
                $this->g * 255,
                $this->b * 255
            );
        }

        return sprintf(
            'rgba(%d, %d, %d, %d)',
            $this->r * 255,
            $this->g * 255,
            $this->b * 255,
            $this->a * 255
        );
    }

    /**
     * @inheritdoc
     */
    public function hash() {
        $hash = $this->r * 255;
        $hash <<= 3;

        $hash += $this->g * 255;
        $hash <<= 3;

        $hash += $this->b * 255;
        $hash <<= 3;

        $hash += $this->a * 255;
        $hash <<= 3;

        return $hash;
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
}