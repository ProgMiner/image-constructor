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
 * Class PositionTransform
 *
 * @package ImageConstructor
 */
class PositionTransform implements Transform {

    /**
     * @var int $x X transform position
     * @var int $y Y transform position
     */
    protected $x, $y;

    public function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @inheritdoc
     */
    public function render(Image $img, ?Image $bg = null): Image {
        $ret = is_null($bg)?
            Utility::transparentImage($img->getWidth() + $this->x, $img->getHeight() + $this->y):
            clone $bg;

        $width = min($img->getWidth() + $this->x, $bg->getWidth());
        $height = min($img->getHeight() + $this->y, $bg->getHeight());

        for ($x = max(0, $this->x); $x < $width; ++$x) {
            for ($y = max(0, $this->y); $y < $height; ++$y) {
                $ret->setPixelColor($x, $y, Utility::mixColors(
                    $bg->getPixelColor($x, $y),
                    $img->getPixelColor($x - $this->x, $y - $this->y)
                ));
            }
        }

        return $ret;
    }
}