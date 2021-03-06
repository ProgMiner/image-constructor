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

use Ds\Map;

/**
 * Class ConstructionSprite
 *
 * Sprite, consisting of several Sprites.
 *
 * @package ImageConstructor
 */
class ConstructionSprite implements Sprite, \Serializable {

    /**
     * @var Map Map of child Sprites and Transforms for them
     */
    public $sprites;

    public function __construct(?Map $sprites = null) {
        $this->sprites = $sprites ?? new Map();
    }

    public function __clone() {
        $this->sprites = clone $this->sprites;
    }

    /**
     * @inheritdoc
     */
    public function render(): Image {
        $current = null;

        foreach ($this->sprites as $transform => $sprite) {
            if (!$transform instanceof Transform || !$sprite instanceof Sprite) {
                continue;
            }

            $current = $transform->render($sprite->render(), $current);
        }

        return $current;
    }

    /**
     * @inheritdoc
     */
    public function serialize() {
        return serialize($this->sprites->pairs()->toArray());
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized) {
        $this->sprites = new Map();

        foreach (unserialize($serialized) as $pair) {
            $this->sprites->put($pair->key, $pair->value);
        }
    }
}