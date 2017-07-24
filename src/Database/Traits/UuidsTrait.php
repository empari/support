<?php
namespace Empari\Support\Database\Traits;

use Webpatser\Uuid\Uuid;

/**
 * Class Uuids
 * Generate UUID for primary Keys
 *
 * @package Naweby\Support\Traits
 * @author Adriano Santos <adrianodrix@gmail.com>
 */
trait UuidsTrait
{
    /**
     * Boot function from laravel.
     */
    protected static function bootUuidsTrait()
    {
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }
}