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
 * Class TextSprite
 *
 * Sprite, that consist of text.
 *
 * @package ImageConstructor
 */
class TextSprite implements Sprite {

    /**
     * @var string Text to render
     */
    public $text;

    /**
     * @var float Font size in pt
     */
    public $fontSize;

    /**
     * @var Color Color that will be used for drawing text
     */
    public $color;

    /**
     * @var string Font file
     */
    public $fontFile;

    /**
     * @var array Possible array indexes:
     *              - linespacing - float - Defines drawing linespacing
     */
    public $extraInfo = [];

    public function __construct(string $text, int $fontSize, Color $color, ?string $fontFile = null, array $extraInfo = []) {
        $this->text = $text;
        $this->fontSize = $fontSize;
        $this->color = $color;
        $this->fontFile = $fontFile;
        $this->extraInfo = $extraInfo;
    }

    /**
     * Renders object as an image.
     *
     * @return Image Image
     */
    public function render(): Image {
        $ftbbox = imageftbbox($this->fontSize, 0, $this->fontFile, $this->text, $this->extraInfo);

        $width = max($ftbbox[2] - $ftbbox[6], 1);
        $height = max($ftbbox[3] - $ftbbox[7], 1);

        // Getting an offset for the first symbol of text
        $ftbbox = imageftbbox($this->fontSize, 0, $this->fontFile, $this->text[0] ?? '', $this->extraInfo);
        $offset = $ftbbox[3] - $ftbbox[7];

        $image = Utility::transparentImage($width, $height);
        imagefttext(
            $image->getResource(),
            $this->fontSize,
            0,
            0,
            $offset,
            $this->color->allocate($image->getResource()),
            $this->fontFile,
            $this->text,
            $this->extraInfo
        );

        return $image;
    }
}