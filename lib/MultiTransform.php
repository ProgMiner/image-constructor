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

use Ds\Vector;

/**
 * Class MultiTransform
 *
 * Transform that applies several transforms on rendering.
 *
 * @package ImageConstructor
 */
class MultiTransform implements Transform {

    /**
     * @var Vector Transformations that will be applied when rendering
     */
    public $transforms;

    public function __construct(?Vector $transforms = null) {
        $this->transforms = $transforms ?? new Vector();
    }

    /**
     * @inheritdoc
     */
    public function render(Image $img, ?Image $bg = null): Image {
        $current = clone $img;

        foreach ($this->transforms as $transform) {
            if (!$transform instanceof Transform) {
                continue;
            }

            $current = $transform->render($current);
        }

        if (!is_null($bg)) {
            $img = (new DirectTransform())->render($img, $bg);
        }

        return $img;
    }
}