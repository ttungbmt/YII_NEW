<?= '<?xml version="1.0" encoding="UTF-8"?><StyledLayerDescriptor xmlns="http://www.opengis.net/sld" xmlns:sld="http://www.opengis.net/sld" xmlns:gml="http://www.opengis.net/gml" xmlns:ogc="http://www.opengis.net/ogc" version="1.0.0">' ?>
<NamedLayer>
    <Name><?=$name?></Name>
    <UserStyle>
        <Name><?=$name?></Name>
        <FeatureTypeStyle>
            <?php foreach ($classes as $k => $c):?>
                <Rule>
                    <Filter>
                        <And>
                            <?php if($k === 0):?>
                                <PropertyIsGreaterThanOrEqualTo>
                                    <PropertyName>val</PropertyName>
                                    <Literal><?=$c['start']?></Literal>
                                </PropertyIsGreaterThanOrEqualTo>
                            <?php else:;?>
                                <PropertyIsGreaterThan>
                                    <PropertyName>val</PropertyName>
                                    <Literal><?=$c['start']?></Literal>
                                </PropertyIsGreaterThan>
                            <?php endif;?>
                            <PropertyIsLessThanOrEqualTo>
                                <PropertyName>val</PropertyName>
                                <Literal><?=$c['end']?></Literal>
                            </PropertyIsLessThanOrEqualTo>
                        </And>
                    </Filter>
                    <PolygonSymbolizer>
                        <Fill>
                            <CssParameter name="fill"><?=$c['color']?></CssParameter>
                        </Fill>
                        <Stroke>
                            <CssParameter name="stroke">#ffffff</CssParameter>
                            <CssParameter name="stroke-width">0</CssParameter>
                        </Stroke>
                    </PolygonSymbolizer>
                </Rule>
            <?php endforeach;?>
            <VendorOption name="ruleEvaluation">first</VendorOption>
        </FeatureTypeStyle>
    </UserStyle>
</NamedLayer>
</StyledLayerDescriptor>

