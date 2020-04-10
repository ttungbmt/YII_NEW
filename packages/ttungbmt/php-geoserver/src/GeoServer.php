<?php
namespace ttungbmt\GeoServer;

use OneOffTech\GeoServer\Auth\Authentication;
use OneOffTech\GeoServer\GeoFile;
use OneOffTech\GeoServer\Http\InteractsWithHttp;
use OneOffTech\GeoServer\Http\Routes;
use OneOffTech\GeoServer\Options;

class GeoServer
{
    use InteractsWithHttp;
    public $client;
    protected $workspace;
    protected $routes;

    public function __construct()
    {
        $this->workspace = 'drought';
        $url = 'http://localhost:8080/geoserver/';
        $authentication = new Authentication('admin', 'geoserver');
        $options = new Options($authentication);

        $this->httpClient = $options->httpClient;
        $this->messageFactory = $options->messageFactory;
        $this->routes = new Routes($url);

        $geoserver = \OneOffTech\GeoServer\GeoServer::build($url, $this->workspace, $authentication);
        $this->client = $geoserver;
    }

    public function manifest()
    {
        $route = $this->routes->url('about/manifest?key=GeoServerModule');
        $response = $this->get($route);
        return $response->about->resource;
    }


    public function fonts()
    {
        $route = $this->routes->url('fonts');
        $response = $this->get($route);
        return $response->fonts;
    }

    public function layers()
    {
        $route = $this->routes->url('layers');
        $response = $this->get($route);
        return $response->layers->layer;
    }

    public function styles()
    {
        $styles = $this->client->styles();

        $route = $this->routes->url('styles');
        $response = $this->get($route);
        return $response->styles->style;
    }

    public function style(...$args)
    {
        $route = $this->routes->url("workspaces/drought/styles/grid.sld");
        $request = $this->messageFactory->createRequest('GET', $route, []);
        $response = $this->handleRequest($request);
        $stream = $response->getBody();
        return $stream->getContents();


//        dd(file_get_contents('http://localhost:8080/geoserver/rest/workspaces/drought/styles/grid.css'));

//        $response = $this->get($route);
//        dd($response);

//        return $this->client->style(...$args);
    }
}