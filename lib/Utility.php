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
 * Class Utility
 *
 * Some utilities
 *
 * @package ImageConstructor
 */
abstract class Utility {

    /**
     * Creates empty transparent GD image
     *
     * @link http://php.net/manual/function.imagefill.php#110186
     *
     * @param Pair $size Image size
     *
     * @return resource GD image
     */
    public static function transparentGD(Pair $size) {
        $image = imagecreatetruecolor($size->key, $size->value);
        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);
        imagesavealpha($image, true);

        return $image;
    }

    /**
     * Converts Image to GD image
     *
     * @param Image $image Image to convert
     *
     * @return resource GD image
     */
    public static function imageToGD(Image $image) {
        $gd = self::transparentGD($image->getSize());

        for ($x = 0; $x < $image->getSize()->key; ++$x) {
            for ($y = 0; $y < $image->getSize()->value; ++$y) {
                imagesetpixel($gd, $x, $y, $image->getPixels()[$x][$y]->gdAllocate($gd));
            }
        }

        return $gd;
    }

    /**
     * Converts GD image to Image
     *
     * @param resource $gd GD image
     * @param bool $alpha If true tries to get alpha component of pixels
     * @param bool $destroy If true destroys GD image
     *
     * @return Image
     */
    public static function gdToImage($gd, bool $alpha = true, bool $destroy = true): Image {
        $pixels = [];

        for ($x = 0; $x < imagesx($gd); ++$x) {
            $pixels[$x] = [];

            for ($y = 0; $y < imagesy($gd); ++$y) {
                $pixels[$x][$y] = Color::fromGD($gd, imagecolorat($gd, $x, $y));
            }
        }

        if ($destroy) {
            imagedestroy($gd);
        }

        return new BaseImage($pixels);
    }
}