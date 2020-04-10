<?php

use Illuminate\Support\Collection;

Collection::macro('recursive', function () {
    return $this->map(function ($value) {
        if (is_array($value) || is_object($value)) {
            return collect($value)->recursive();
        }

        return $value;
    });
});

Collection::macro('indexBy', function ($name) {
    return $this->keyBy($name);
});

Collection::macro('filterBlank', function ($names) {
    $fn_blank = function ($item){return $item != '';};
    $data = $this->all();
    foreach ($names as $k => $n){
        $this->put($n, array_filter($this->nget($n), $fn_blank));
    }
    return $this;
});

Collection::macro('filterEmpty', function () {
    return $this->filter(function ($item){
        return !empty($item);
    });
});

Collection::macro('nget', function ($key, $default = null) {
    return array_get($this->all(), $key, $default);
});



