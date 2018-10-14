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
 * Class Image
 *
 * @package ImageConstructor
 */
class Image {

    /**
     * @var resource GD image
     */
    protected $resource;

    public function __construct($resource) {
        $this->resource = $resource;
    }

    public function getResource() {
        return $this->resource;
    }

    public function getWidth(): int {
        return imagesx($this->resource);
    }

    public function getHeight(): int {
        return imagesy($this->resource);
    }

    public function getPixelColor(int $x, int $y): Color {
        return Color::fromIndex($this, imagecolorat($this->resource, $x, $y));
    }

    public function setPixelColor(int $x, int $y, Color $color) {
        return imagesetpixel($this->resource, $x, $y, $color->allocate($this));
    }

    public function __clone() {
        $this->resource = $this->cloneResource($this->resource);
    }

    /**
     * https://stackoverflow.com/questions/12605768/how-to-clone-a-gd-resource-in-php
     *
     * @param resource $img
     *
     * @return resource
     */
    private function cloneResource($img) {
        $w = imagesx($img);
        $h = imagesy($img);

        // Get the transparent color from a 256 palette image.
        $trans = imagecolortransparent($img);

        if (imageistruecolor($img)) { // If this is a true color image...
            $clone = imagecreatetruecolor($w, $h);
            imagealphablending($clone, false);
            imagesavealpha($clone, true);
        } else { // If this is a 256 color palette image...
            $clone = imagecreate($w, $h);

            // If the image has transparency...
            if ($trans >= 0) {
                $rgb = imagecolorsforindex($img, $trans);

                imagesavealpha($clone, true);
                $trans_index = imagecolorallocatealpha($clone, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                imagefill($clone, 0, 0, $trans_index);
            }
        }

        // Create the Clone!!
        imagecopy($clone, $img, 0, 0, 0, 0, $w, $h);

        return $clone;
    }
}