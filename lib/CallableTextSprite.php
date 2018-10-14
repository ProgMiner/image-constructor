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
 * Class CallableTextSprite
 *
 * Sprite that calls a callable on rendering.
 *
 * @package ImageConstructor
 */
class CallableTextSprite extends TextSprite {

    /**
     * @var callable Callable that returns a text for rendering
     */
    protected $callable;

    public function __construct(callable $callable, int $fontSize, Color $color, ?string $fontFile = null, array $extraInfo = []) {
        parent::__construct('', $fontSize, $color, $fontFile, $extraInfo);

        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function render(): Image {
        $this->text = call_user_func($this->callable);

        return parent::render();
    }
}