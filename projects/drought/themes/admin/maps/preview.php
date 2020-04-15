
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/geoserver/openlayers3/ol.css" type="text/css">
    <style>
        .ol-zoom {
            top: 52px;
        }
        .ol-toggle-options {
            z-index: 1000;
            background: rgba(255,255,255,0.4);
            border-radius: 4px;
            padding: 2px;
            position: absolute;
            left: 8px;
            top: 8px;
        }
        #updateFilterButton, #resetFilterButton {
            height: 22px;
            width: 22px;
            text-align: center;
            text-decoration: none !important;
            line-height: 22px;
            margin: 1px;
            font-family: 'Lucida Grande',Verdana,Geneva,Lucida,Arial,Helvetica,sans-serif;
            font-weight: bold !important;
            background: rgba(0,60,136,0.5);
            color: white !important;
            padding: 2px;
        }
        .ol-toggle-options a {
            background: rgba(0,60,136,0.5);
            color: white;
            display: block;
            font-family: 'Lucida Grande',Verdana,Geneva,Lucida,Arial,Helvetica,sans-serif;
            font-size: 19px;
            font-weight: bold;
            height: 22px;
            line-height: 11px;
            margin: 1px;
            padding: 0;
            text-align: center;
            text-decoration: none;
            width: 22px;
            border-radius: 2px;
        }
        .ol-toggle-options a:hover {
            color: #fff;
            text-decoration: none;
            background: rgba(0,60,136,0.7);
        }
        body {
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-size: small;
        }
        iframe {
            width: 100%;
            height: 250px;
            border: none;
        }
        /* Toolbar styles */
        #toolbar {
            position: relative;
            padding-bottom: 0.5em;
        }
        #toolbar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        #toolbar ul li {
            float: left;
            padding-right: 1em;
            padding-bottom: 0.5em;
        }
        #toolbar ul li a {
            font-weight: bold;
            font-size: smaller;
            vertical-align: middle;
            color: black;
            text-decoration: none;
        }
        #toolbar ul li a:hover {
            text-decoration: underline;
        }
        #toolbar ul li * {
            vertical-align: middle;
        }
        #map {
            clear: both;
            position: relative;
            width: 100%;
            height: 662px;
            border: 2px solid #2196f3;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        #wrapper {
            width: 100%;
        }
        #location {
            float: right;
        }
        /* Styles used by the default GetFeatureInfo output, added to make IE happy */
        table.featureInfo, table.featureInfo td, table.featureInfo th {
            border: 1px solid #ddd;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            font-size: 90%;
            padding: .2em .1em;
        }
        table.featureInfo th {
            padding: .2em .2em;
            font-weight: bold;
            background: #eee;
        }
        table.featureInfo td {
            background: #fff;
        }
        table.featureInfo tr.odd td {
            background: #eee;
        }
        table.featureInfo caption {
            text-align: left;
            font-size: 100%;
            font-weight: bold;
            padding: .2em .2em;
        }
    </style>
    <script src="/geoserver/openlayers3/ol.js" type="text/javascript"></script>
    <title>OpenLayers map preview</title>
</head>
<body>
<div id="toolbar" style="display: none;">
    <ul>
        <li>
            <a>Filter:</a>
            <select id="filterType">
                <option value="cql">CQL</option>
                <option value="ogc">OGC</option>
                <option value="fid">FeatureID</option>
            </select>
            <input type="text" size="80" id="filter"/>
            <a id="updateFilterButton" href="#" onClick="updateFilter()" title="Apply filter">Apply</a>
            <a id="resetFilterButton" href="#" onClick="resetFilter()" title="Reset filter">Reset</a>
        </li>
    </ul>
</div>
<div id="map">
    <div class="ol-toggle-options ol-unselectable"><a title="Toggle options toolbar" onClick="toggleControlPanel()" href="#toggle">...</a></div>
</div>
<div id="wrapper">
    <div id="location"></div>
    <div id="scale">
    </div>
    <div id="nodelist">
        <em>Click on the map to get feature info</em>
    </div>
    <script type="text/javascript">
        var pureCoverage = false;
        // if this is just a coverage or a group of them, disable a few items,
        // and default to jpeg format
        var format = 'image/png';
        var bounds = [107.39808379291, 10.5776624705078,
            109.230646972678, 12.1586973706998];
        if (pureCoverage) {
            document.getElementById('antialiasSelector').disabled = true;
            document.getElementById('jpeg').selected = true;
            format = "image/jpeg";
        }

        var supportsFiltering = true;
        if (!supportsFiltering) {
            document.getElementById('filterType').disabled = true;
            document.getElementById('filter').disabled = true;
            document.getElementById('updateFilterButton').disabled = true;
            document.getElementById('resetFilterButton').disabled = true;
        }

        var mousePositionControl = new ol.control.MousePosition({
            className: 'custom-mouse-position',
            target: document.getElementById('location'),
            coordinateFormat: ol.coordinate.createStringXY(5),
            undefinedHTML: '&nbsp;'
        });
        var untiled = new ol.layer.Image({
            source: new ol.source.ImageWMS({
                ratio: 1,
                url: '/geoserver/ows?',
                params: {'FORMAT': format,
                    'VERSION': '1.1.1',
                    "LAYERS": '<?=$layers?>',
                    "exceptions": 'application/vnd.ogc.se_inimage',
                }
            })
        });

        var projection = new ol.proj.Projection({
            code: 'EPSG:4326',
            units: 'degrees',
            axisOrientation: 'neu',
            global: true
        });
        var map = new ol.Map({
            controls: ol.control.defaults({
                attribution: false
            }).extend([mousePositionControl]),
            target: 'map',
            layers: [
                untiled,
            ],
            view: new ol.View({
                projection: projection
            })
        });
        map.getView().on('change:resolution', function(evt) {
            var resolution = evt.target.get('resolution');
            var units = map.getView().getProjection().getUnits();
            var dpi = 25.4 / 0.28;
            var mpu = ol.proj.METERS_PER_UNIT[units];
            var scale = resolution * mpu * 39.37 * dpi;
            if (scale >= 9500 && scale <= 950000) {
                scale = Math.round(scale / 1000) + "K";
            } else if (scale >= 950000) {
                scale = Math.round(scale / 1000000) + "M";
            } else {
                scale = Math.round(scale);
            }
            document.getElementById('scale').innerHTML = "Scale = 1 : " + scale;
        });
        map.getView().fit(bounds, map.getSize());
        map.on('singleclick', function(evt) {
            document.getElementById('nodelist').innerHTML = "Loading... please wait...";
            var view = map.getView();
            var viewResolution = view.getResolution();
            var source = untiled.get('visible') ? untiled.getSource() : tiled.getSource();
            var url = source.getGetFeatureInfoUrl(
                evt.coordinate, viewResolution, view.getProjection(),
                {'INFO_FORMAT': 'text/html', 'FEATURE_COUNT': 50});
            if (url) {
                document.getElementById('nodelist').innerHTML = '<iframe seamless src="' + url + '"></iframe>';
            }
        });

        // sets the chosen WMS version
        function setWMSVersion(wmsVersion) {
            map.getLayers().forEach(function(lyr) {
                lyr.getSource().updateParams({'VERSION': wmsVersion});
            });
            if(wmsVersion == "1.3.0") {
                origin = bounds[1] + ',' + bounds[0];
            } else {
                origin = bounds[0] + ',' + bounds[1];
            }
            tiled.getSource().updateParams({'tilesOrigin': origin});
        }

        // Tiling mode, can be 'tiled' or 'untiled'
        function setTileMode(tilingMode) {
            if (tilingMode == 'tiled') {
                untiled.set('visible', false);
                tiled.set('visible', true);
            } else {
                tiled.set('visible', false);
                untiled.set('visible', true);
            }
        }

        function setAntialiasMode(mode) {
            map.getLayers().forEach(function(lyr) {
                lyr.getSource().updateParams({'FORMAT_OPTIONS': 'antialias:' + mode});
            });
        }

        // changes the current tile format
        function setImageFormat(mime) {
            map.getLayers().forEach(function(lyr) {
                lyr.getSource().updateParams({'FORMAT': mime});
            });
        }

        function setStyle(style){
            map.getLayers().forEach(function(lyr) {
                lyr.getSource().updateParams({'STYLES': style});
            });
        }

        function setWidth(size){
            var mapDiv = document.getElementById('map');
            var wrapper = document.getElementById('wrapper');

            if (size == "auto") {
                // reset back to the default value
                mapDiv.style.width = null;
                wrapper.style.width = null;
            }
            else {
                mapDiv.style.width = size + "px";
                wrapper.style.width = size + "px";
            }
            // notify OL that we changed the size of the map div
            map.updateSize();
        }

        function setHeight(size){
            var mapDiv = document.getElementById('map');
            if (size == "auto") {
                // reset back to the default value
                mapDiv.style.height = null;
            }
            else {
                mapDiv.style.height = size + "px";
            }
            // notify OL that we changed the size of the map div
            map.updateSize();
        }

        function updateFilter(){
            if (!supportsFiltering) {
                return;
            }
            var filterType = document.getElementById('filterType').value;
            var filter = document.getElementById('filter').value;
            // by default, reset all filters
            var filterParams = {
                'FILTER': null,
                'CQL_FILTER': null,
                'FEATUREID': null
            };
            if (filter.replace(/^\s\s*/, '').replace(/\s\s*$/, '') != "") {
                if (filterType == "cql") {
                    filterParams["CQL_FILTER"] = filter;
                }
                if (filterType == "ogc") {
                    filterParams["FILTER"] = filter;
                }
                if (filterType == "fid")
                    filterParams["FEATUREID"] = filter;
            }
            // merge the new filter definitions
            map.getLayers().forEach(function(lyr) {
                lyr.getSource().updateParams(filterParams);
            });
        }

        function resetFilter() {
            if (!supportsFiltering) {
                return;
            }
            document.getElementById('filter').value = "";
            updateFilter();
        }

        // shows/hide the control panel
        function toggleControlPanel(){
            var toolbar = document.getElementById("toolbar");
            if (toolbar.style.display == "none") {
                toolbar.style.display = "block";
            }
            else {
                toolbar.style.display = "none";
            }
            map.updateSize()
        }

    </script>
</body>
</html>
