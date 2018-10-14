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

    /**
     * @link https://habr.com/post/98743/
     */
    protected function mixColors(Color $a, Color $b): Color {
        $alpha = $b->a + (255 - $b->a) * $a->a;

        // To avoid divide on zero
        if ($alpha == 0) {
            return new Color(0, 0, 0, 0);
        }

        $k = $b->a / $alpha;

        return new Color(
            ($a->r - $b->r) * $k + $b->r,
            ($a->g - $b->g) * $k + $b->g,
            ($a->b - $b->b) * $k + $b->b,
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

        $size = new Pair(
            min($img->getSize()->key, $bg->getSize()->key),
            min($img->getSize()->value, $bg->getSize()->value)
        );

        $pixels = $bg->getPixels();
        for ($x = 0; $x < $size->key; ++$x) {
            for ($y = 0; $y < $size->value; ++$y) {
                $pixels[$x][$y] = $this->mixColors($pixels[$x][$y], $img->getPixels()[$x][$y]);
            }
        }

        return new BaseImage($pixels);
    }
}