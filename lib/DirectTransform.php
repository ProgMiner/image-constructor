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
 * Class DirectTransform
 *
 * Transform that not transform images.
 *
 * Use this transform for drawing one image
 * to another without transformation.
 *
 * @package ImageConstructor
 */
class DirectTransform implements Transform {

    private function a(Color $color): float {
        return (127 - $color->a()) / 127;
    }

    /**
     * @link https://habr.com/post/98743/
     *
     * @param Color $a
     * @param Color $b
     *
     * @return Color
     */
    protected function mixColors(Color $a, Color $b): Color {
        $alpha = $this->a($b) + (1 - $this->a($b)) * $this->a($a);

        // To avoid divide on zero
        if ($alpha == 0) {
            return new Color(0, 0, 0, 0);
        }

        $k = $this->a($b) / $alpha;

        return new Color(
            $a->r() + ($b->r() - $a->r()) * $k,
            $a->g() + ($b->g() - $a->g()) * $k,
            $a->b() + ($b->b() - $a->b()) * $k,
            $alpha
        );
    }

    /**
     * @inheritdoc
     */
    public function render(Image $img, ?Image $bg = null): Image {
        if (is_null($bg)) {
            return clone $img;
        }

        $width = min($img->getWidth(), $bg->getWidth());
        $height = min($img->getHeight(), $bg->getHeight());

        $ret = clone $bg;
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $ret->setPixelColor($x, $y, $this->mixColors($bg->getPixelColor($x, $y), $img->getPixelColor($x, $y)));
            }
        }

        // I don't know is this works:
        //   imagealphablending($ret->getResource(), true);
        //   imagecopy($ret->getResource(), $img->getResource(), 0, 0, 0, 0, $width, $height);

        return $ret;
    }
}