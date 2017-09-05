<?php namespace Empari\Support\Annotations\Mapping;

/**
 * Class Action
 *
 * @Annotation
 * @Target("METHOD")
 * @package Empari\Support\Annotations\Mapping
 */
class Action
{
    public $name;
    public $description;
}