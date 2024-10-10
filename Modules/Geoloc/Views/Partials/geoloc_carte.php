<div class="col-md-6">
    <div id="map_user" tabindex="300" style="width:100%; height:400px;" lang="<?= language_iso(1, 0, 0); ?>">
        <div id="ol_popup"></div>
    </div>
    <script type="module">
        //<![CDATA[
            if (typeof ol == "undefined") {
                $("head").append($("<script />").attr({
                    "type": "text/javascript",
                    "src": "<?= site_url('assets/shared/ol/ol.js'); ?>"
                }));
            }
                
            $(function() {
                var iconFeature = new ol.Feature({
                        geometry: new ol.geom.Point(
                            ol.proj.fromLonLat([<?= $longitude; ?>, <?= $latitude; ?>])
                        ),
                        name: "<?= $uname; ?>"
                    }),
                    iconStyle = new ol.style.Style({
                        image: new ol.style.Icon({
                            src: "<?= config('geoloc.config.ch_img') . config('geoloc.config.nm_img_mbcg'); ?>"
                        })
                    });
                    iconFeature.setStyle(iconStyle);

                var vectorSource = new ol.source.Vector({
                        features: [iconFeature]
                    }),
                    vectorLayer = new ol.layer.Vector({
                        source: vectorSource
                    }),
                    map = new ol.Map({
                        interactions: new ol.interaction.defaults({
                            constrainResolution: true,
                            onFocusOnly: true
                        }),
                        target: document.getElementById("map_user"),
                        layers: [
                            new ol.layer.Tile({
                                source: new ol.source.OSM()
                            })
                        ],
                        view: new ol.View({
                            center: ol.proj.fromLonLat([<?= $longitude; ?>, <?= $latitude; ?>]),
                            zoom: 12
                        })
                    });

                    // Adding a marker on the map
                    map.addLayer(vectorLayer);

                var element = document.getElementById("ol_popup");
                var popup = new ol.Overlay({
                    element: element,
                    positioning: "bottom-center",
                    stopEvent: false,
                    offset: [0, -20]
                });
                map.addOverlay(popup);

                // display popup on click
                map.on("click", function(evt) {
                    var feature = map.forEachFeatureAtPixel(evt.pixel,
                        function(feature) {
                            return feature;
                        });
                    if (feature) {
                        var coordinates = feature.getGeometry().getCoordinates();
                            popup.setPosition(coordinates);

                        $(element).popover({
                            placement: "top",
                            html: true,
                            content: feature.get("name")
                        });
                        $(element).popover("show");
                    } else {
                        $(element).popover("hide");
                    }
                });

                // change mouse cursor when over marker
                map.on("pointermove", function(e) {
                    if (e.dragging) {
                        $(element).popover("hide");
                        return;
                    }
                    var pixel = map.getEventPixel(e.originalEvent);
                });

                // Create the graticule component
                var graticule = new ol.layer.Graticule();
                    graticule.setMap(map);

                <?= $import_geoloc_data_js; ?>

                const targ = map.getTarget();
                const lang = targ.lang;
                
                for (var i in dic) {
                    if (dic.hasOwnProperty(i)) {
                        $("#map_user " + dic[i].cla).prop("title", dic[i][lang]);
                    }
                }

                $("#map_user .ol-zoom-in, #map_user .ol-zoom-out").tooltip({
                    placement: "right",
                    container: "#map_user",
                });

                $("#map_user .ol-rotate-reset, #map_user .ol-attribution button[title]").tooltip({
                    placement: "left",
                    container: "#map_user",
                });
            });
        //]]>
    </script>
    <div class="mt-3">
        <a href="<?= site_url('geoloc?op=geoloc'); ?>">
            <i class="fa fa-globe fa-lg"></i>
            &nbsp;<?= __d('feoloc', 'Carte'); ?>
        </a>
        <?php if (guard('admin')): ?>
            <a href="<?= site_url('admin/geoloc/geoloc_set?op=geoloc_set'); ?>">
                <i class="fa fa-cogs fa-lg ms-3"></i>
                &nbsp;<?= __d('geoloc', 'Admin'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>