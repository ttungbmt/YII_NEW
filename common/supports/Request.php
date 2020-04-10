<?php
namespace common\supports;


class Request extends \yii\web\Request
{
    protected $http;

    public function __construct()
    {
        parent::__construct();

        $this->http = new \Illuminate\Http\Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
    }

    public function input(...$args)
    {
        return $this->http->input(...$args);
    }

    public function all(...$args)
    {
        return $this->http->all(...$args);
    }

    public function only(...$args)
    {
        return $this->http->only(...$args);
    }

    public function has(...$args){
        return $this->http->has(...$args);
    }

    public function filled(...$args){
        return $this->http->filled(...$args);
    }

    public function except(...$args){
        return $this->http->except(...$args);
    }
}