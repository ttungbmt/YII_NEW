<?php

namespace ttungbmt\gdal;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Illuminate\Support\Traits\Macroable;

class Command
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The underlying string value.
     *
     * @var string
     */
    protected $value;

    /**
     * Create a new instance of the class.
     *
     * @param string $value
     * @return void
     */
    public function __construct($value = '')
    {
        $this->value = (string)$value;
    }

    public function clear()
    {
        $this->value = '';

        return $this;
    }


    public function add(...$args)
    {
        $prefix = function ($v = null){
            if($v && Str::of($v)->is('=*')){
                return '';
            }
            return empty($this->value) ? '' : ' ';
        };

        foreach ($args as $k => $v) {
            if (is_string($v)) {
                $this->append($prefix().$v);
            } elseif (is_array($v)) {
                foreach ($v as $k1 => $p) {
                    if (!is_null($v) && $v !== '') {
                        if (is_numeric($k1)) {
                            $this->append($prefix().$p);
                        }

                        if (is_string($k1)) {
                            $this->append($prefix().$k1);
                            $this->append($prefix($p).$p);
                        }
                    }
                }
            }

        }

        $this->trim();
    }

    /**
     * Execute a method against a new pending request instance.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        $this->value = (string)tap(new Stringable($this->value), function ($str) {
        })->{$method}(...$parameters);

        return $this;
    }

    /**
     * Get the raw string value.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }
}