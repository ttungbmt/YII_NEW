# Leaflet.Locate

[![Bower version](https://badge.fury.io/bo/leaflet.locatecontrol.svg)](http://badge.fury.io/bo/leaflet.locatecontrol)
[![npm version](https://badge.fury.io/js/leaflet.locatecontrol.svg)](http://badge.fury.io/js/leaflet.locatecontrol)

A useful control to geolocate the user with many options. Official [Leaflet](http://leafletjs.com/plugins.html#geolocation) and [MapBox plugin](https://www.mapbox.com/mapbox.js/example/v1.0.0/leaflet-locatecontrol/).

Tested with [Leaflet](http://leafletjs.com/) 0.7 in Firefox, Webkit and mobile Webkit. Tested with [Font Awesome](https://fortawesome.github.io/Font-Awesome/) 4.3.0.

**v0.34.0 introduced breaking changes to the API. Please check your code!**


## Demo

Check out the demo at http://domoritz.github.io/leaflet-locatecontrol/demo/


## Usage

### Set up:

tl;dr

1. Get CSS and JavaScript files
2. Include CSS and JavaScript files
3. Initialize plugin


#### Download JavaScript and CSS files

For testing purposes and development, you can use the latest version directly from my repository.

For production environments, use [Bower](http://bower.io/) and run `bower install leaflet.locatecontrol` or [download the files from this repository](https://github.com/domoritz/leaflet-locatecontrol/archive/gh-pages.zip). Bower will always download the latest version and keep the code up to date. The original JS and CSS files are in [`\src`](https://github.com/domoritz/leaflet-locatecontrol/tree/gh-pages/src) and the minified versions suitable for production are in [`\dist`](https://github.com/domoritz/leaflet-locatecontrol/tree/gh-pages/dist).

You can also get the latest version of the plugin with [npm](https://www.npmjs.org/). This plugin is available in the [npm repository](https://www.npmjs.org/package/leaflet.locatecontrol). Just run `npm install leaflet.locatecontrol`.

The control is [available from JsDelivr CDN](https://www.jsdelivr.com/projects/leaflet.locatecontrol). If you don't need the latest version, you can use the [mapbox CDN](https://www.mapbox.com/mapbox.js/plugins/#leaflet-locatecontrol).


#### Add the JavaScript and CSS files

The control uses [Font Awesome](https://fortawesome.github.io/Font-Awesome/) for the icons and if you don't have it included yet, you can use the CSS from the CDN.

Then include the CSS and JavaScript files.

This example shows how to include font awesome from a CDN and the locate control files directly from github. In production, prefer using bower or the Mapbox or JsDelivr CDN.

```html
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://domoritz.github.io/leaflet-locatecontrol/dist/L.Control.Locate.min.css" />

<script src="https://domoritz.github.io/leaflet-locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>
```


#### Add the following snippet to your map initialization:

This snippet adds the control to the map. You can pass also pass a configuration.

```js
L.control.locate().addTo(map);
```


### Possible options

The locate controls inherits options from [Leaflet Controls](http://leafletjs.com/reference.html#control-options).

```js
L.control.locate({
	position: 'topleft',  // set the location of the control
    layer: undefined,  // use your own layer for the location marker, creates a new layer by default
    drawCircle: true,  // controls whether a circle is drawn that shows the uncertainty about the location
    follow: false,  // follow the user's location
    setView: true, // automatically sets the map view to the user's location, enabled if `follow` is true
    keepCurrentZoomLevel: false, // keep the current map zoom level when displaying the user's location. (if `false`, use maxZoom)
    stopFollowingOnDrag: false, // stop following when the map is dragged if `follow` is true (deprecated, see below)
    remainActive: false, // if true locate control remains active on click even if the user's location is in view.
    markerClass: L.circleMarker, // L.circleMarker or L.marker
    circleStyle: {},  // change the style of the circle around the user's location
    markerStyle: {},
    followCircleStyle: {},  // set difference for the style of the circle around the user's location while following
    followMarkerStyle: {},
    icon: 'fa fa-map-marker',  // class for icon, fa-location-arrow or fa-map-marker
    iconLoading: 'fa fa-spinner fa-spin',  // class for loading icon
    iconElementTag: 'span',  // tag for the icon element, span or i
    circlePadding: [0, 0], // padding around accuracy circle, value is passed to setBounds
    metric: true,  // use metric or imperial units
    onLocationError: function(err) {alert(err.message)},  // define an error callback function
    onLocationOutsideMapBounds:  function(context) { // called when outside map boundaries
            alert(context.options.strings.outsideMapBoundsMsg);
    },
    showPopup: true, // display a popup when the user click on the inner marker
    strings: {
        title: "Show me where I am",  // title of the locate control
        metersUnit: "meters", // string for metric units
        feetUnit: "feet", // string for imperial units
        popup: "You are within {distance} {unit} from this point",  // text to appear if user clicks on circle
        outsideMapBoundsMsg: "You seem located outside the boundaries of the map" // default message for onLocationOutsideMapBounds
    },
    locateOptions: {}  // define location options e.g enableHighAccuracy: true or maxZoom: 10
}).addTo(map);
```


### Methods

You can call `start()` or `stop()` on the locate control object to set the location of page load for example.

```js
// create control and add to map
var lc = L.control.locate().addTo(map);

// request location update and set location
lc.start();
```

You can also use the helper functions to automatically stop following when the map is panned. See the example below.

```js
var lc = L.control.locate().addTo(map);
map.on('dragstart', lc._stopFollowing, lc);
```

Alternatively, you can unload events when not following to avoid unnecessary events.

```js
map.on('startfollowing', function() {
    map.on('dragstart', lc._stopFollowing, lc);
}).on('stopfollowing', function() {
    map.off('dragstart', lc._stopFollowing, lc);
});
```


### Events

The locate control fires `startfollowing` and `stopfollowing` on the map object and passes `self` as data.


### Extending

To customize the behavior of the plugin, use L.extend to override `start`, `stop`, `drawMarker` and/or `removeMarker`. Please be aware that functions may change and customizations become incompatible.

```js
L.Control.MyLocate = L.Control.Locate.extend({
   drawMarker: function() {
     // override to customize the marker
   }
});

var lc = new L.Control.MyLocate();
```


### FAQ

#### How do I set the maximum zoom level?

Set the `maxZoom` in `locateOptions` (`keepCurrentZoomLevel` must not be set to true).

```js
map.addControl(L.control.locate({
       locateOptions: {
               maxZoom: 10
}}));
```


## Screenshot

![screenshot](https://raw.github.com/domoritz/leaflet-locatecontrol/gh-pages/screenshot.png "Screenshot showing the locate control")


## Users

Sites that use this locate control:

* [OpenStreetMap](http://www.openstreetmap.org/) on the start page
* [MapBox](https://www.mapbox.com/mapbox.js/example/v1.0.0/leaflet-locatecontrol/)
* [wheelmap.org](http://wheelmap.org/map)
* [OpenMensa](http://openmensa.org/)
* ...


## Developers

Run the demo locally with `grunt serve` and then open [localhost:9000/demo/index.html](http://localhost:9000/demo/index.html).

To generate the minified JS and CSS files, use [grunt](http://gruntjs.com/getting-started) and run `grunt`. However, don't include new minified files or a new version as part of a pull request.


## Making a release (only core developer)

A new version is released with `npm run bump:minor`. Then push the new code with `git push && git push --tags` and publish to npm with `npm publish`.


## Thanks

To all [contributors](https://github.com/domoritz/leaflet-locatecontrol/contributors) and issue reporters.


## License

MIT
