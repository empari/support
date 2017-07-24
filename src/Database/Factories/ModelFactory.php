<?php
namespace Empari\Support\Database\Factories;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factory;

abstract class ModelFactory
{
    /**
     * @var string
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Factory
     */
    protected $factory;

    /**
     * @var \Faker\Generator
     */
    protected $faker;


    public function __construct()
    {
        $this->factory = app()->make(Factory::class);
        $this->faker = FakerFactory::create(app()->getLocale());
    }

    public function define()
    {
        $this->factory->define($this->model, function() {
            return $this->fields();
        });
    }

    /**
     * Return fields with values
     * @return array
     */
    abstract protected function fields();
}