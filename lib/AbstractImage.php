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

use Ds\Pair;

/**
 * Class AbstractImage
 *
 * @package ImageConstructor
 */
abstract class AbstractImage implements Image {

    /**
     * @var Pair Pair of width and height
     */
    protected $size;

    /**
     * @var Color[][] Matrix of pixels
     */
    protected $pixels;

    public function __construct(array $pixels = [[new Color(0, 0, 0)]]) {
        $this->size = new Pair(count($pixels[0] ?? []), count($pixels));
        $this->pixels = $pixels;
    }

    public function __clone() {
        $this->size = clone $this->size;
        $this->pixels = (new \ArrayObject($this->pixels))->getArrayCopy();
    }

    /**
     * @inheritdoc
     */
    public function getSize(): Pair {
        return $this->size;
    }

    /**
     * @inheritdoc
     */
    public function getPixels(): array {
        return $this->pixels;
    }
}