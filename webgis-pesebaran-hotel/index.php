<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link rel="stylesheet" href="css/leaflet.css">
        <link rel="stylesheet" href="css/L.Control.Layers.Tree.css">
        <link rel="stylesheet" href="css/qgis2web.css">
        <link rel="stylesheet" href="css/fontawesome-all.min.css">
        <style>
        html, body, #map {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        </style>
        <title></title>
    </head>
    <body>
        <div id="map">
        </div>
        <script src="js/qgis2web_expressions.js"></script>
        <script src="js/leaflet.js"></script>
        <script src="js/L.Control.Layers.Tree.min.js"></script>
        <script src="js/leaflet.rotatedMarker.js"></script>
        <script src="js/leaflet.pattern.js"></script>
        <script src="js/leaflet-hash.js"></script>
        <script src="js/Autolinker.min.js"></script>
        <script src="js/rbush.min.js"></script>
        <script src="js/labelgun.min.js"></script>
        <script src="js/labels.js"></script>
        <script src="data/ADMINISTRASIKECAMATAN_AR_25K_1.js"></script>
        <script src="data/JALAN_LN_25K_2.js"></script>
        <script src="data/hotel_3.php"></script>
        <script>
        var map = L.map('map', {
            zoomControl:false, maxZoom:28, minZoom:1
        }).fitBounds([[-6.206043671165827,106.71166838558753],[-6.018105657223745,106.98678246623768]]);
        var hash = new L.Hash(map);
        map.attributionControl.setPrefix('<a href="https://github.com/tomchadwin/qgis2web" target="_blank">qgis2web</a> &middot; <a href="https://leafletjs.com" title="A JS library for interactive maps">Leaflet</a> &middot; <a href="https://qgis.org">QGIS</a>');
        var autolinker = new Autolinker({truncate: {length: 30, location: 'smart'}});
        // remove popup's row if "visible-with-data"
        function removeEmptyRowsFromPopupContent(content, feature) {
         var tempDiv = document.createElement('div');
         tempDiv.innerHTML = content;
         var rows = tempDiv.querySelectorAll('tr');
         for (var i = 0; i < rows.length; i++) {
             var td = rows[i].querySelector('td.visible-with-data');
             var key = td ? td.id : '';
             if (td && td.classList.contains('visible-with-data') && feature.properties[key] == null) {
                 rows[i].parentNode.removeChild(rows[i]);
             }
         }
         return tempDiv.innerHTML;
        }
        // add class to format popup if it contains media
		function addClassToPopupIfMedia(content, popup) {
			var tempDiv = document.createElement('div');
			tempDiv.innerHTML = content;
			if (tempDiv.querySelector('td img')) {
				popup._contentNode.classList.add('media');
					// Delay to force the redraw
					setTimeout(function() {
						popup.update();
					}, 10);
			} else {
				popup._contentNode.classList.remove('media');
			}
		}
        var zoomControl = L.control.zoom({
            position: 'topleft'
        }).addTo(map);
        var bounds_group = new L.featureGroup([]);
        function setBounds() {
        }
        map.createPane('pane_OpenStreetMap_0');
        map.getPane('pane_OpenStreetMap_0').style.zIndex = 400;
        var layer_OpenStreetMap_0 = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            pane: 'pane_OpenStreetMap_0',
            opacity: 1.0,
            attribution: '',
            minZoom: 1,
            maxZoom: 28,
            minNativeZoom: 0,
            maxNativeZoom: 19
        });
        layer_OpenStreetMap_0;
        map.addLayer(layer_OpenStreetMap_0);
        function pop_ADMINISTRASIKECAMATAN_AR_25K_1(feature, layer) {
            var popupContent = '<table>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['NAMOBJ'] !== null ? autolinker.link(String(feature.properties['NAMOBJ']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['WADMKK'] !== null ? autolinker.link(String(feature.properties['WADMKK']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['WADMPR'] !== null ? autolinker.link(String(feature.properties['WADMPR']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['SHAPE_Leng'] !== null ? autolinker.link(String(feature.properties['SHAPE_Leng']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['SHAPE_Area'] !== null ? autolinker.link(String(feature.properties['SHAPE_Area']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                </table>';
            var content = removeEmptyRowsFromPopupContent(popupContent, feature);
			layer.on('popupopen', function(e) {
				addClassToPopupIfMedia(content, e.popup);
			});
			layer.bindPopup(content, { maxHeight: 400 });
        }

        function style_ADMINISTRASIKECAMATAN_AR_25K_1_0() {
            return {
                pane: 'pane_ADMINISTRASIKECAMATAN_AR_25K_1',
                opacity: 1,
                color: 'rgba(35,35,35,1.0)',
                dashArray: '',
                lineCap: 'butt',
                lineJoin: 'miter',
                weight: 1.0, 
                fill: true,
                fillOpacity: 1,
                fillColor: 'rgba(225,89,137,1.0)',
                interactive: true,
            }
        }
        map.createPane('pane_ADMINISTRASIKECAMATAN_AR_25K_1');
        map.getPane('pane_ADMINISTRASIKECAMATAN_AR_25K_1').style.zIndex = 401;
        map.getPane('pane_ADMINISTRASIKECAMATAN_AR_25K_1').style['mix-blend-mode'] = 'normal';
        var layer_ADMINISTRASIKECAMATAN_AR_25K_1 = new L.geoJson(json_ADMINISTRASIKECAMATAN_AR_25K_1, {
            attribution: '',
            interactive: true,
            dataVar: 'json_ADMINISTRASIKECAMATAN_AR_25K_1',
            layerName: 'layer_ADMINISTRASIKECAMATAN_AR_25K_1',
            pane: 'pane_ADMINISTRASIKECAMATAN_AR_25K_1',
            onEachFeature: function(feature, layer) {
                // Apply original popup
                pop_ADMINISTRASIKECAMATAN_AR_25K_1(feature, layer);
                
                // Add click handler for iframe communication
                layer.on('click', function(e) {
                    // Check if we're in an iframe
                    if (window.parent && window.parent !== window) {
                        // Prevent default popup opening
                        e.originalEvent.stopPropagation();
                        
                        // Send click coordinates to parent window
                        window.parent.postMessage({
                            type: 'mapClick',
                            lat: e.latlng.lat,
                            lng: e.latlng.lng,
                            kecamatan: feature.properties.NAMOBJ || ''
                        }, '*');
                        
                        // Add a temporary marker to show where clicked
                        if (window.tempMarker) {
                            map.removeLayer(window.tempMarker);
                        }
                        
                        window.tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                            icon: L.icon({
                                iconUrl: 'markers/hotel_3.svg',
                                iconSize: [30, 30],
                                iconAnchor: [15, 15],
                                popupAnchor: [0, -15]
                            })
                        }).addTo(map);
                        
                        window.tempMarker.bindPopup(`
                            <div style="text-align: center;">
                                <strong>Lokasi Hotel Baru</strong><br>
                                Kecamatan: ${feature.properties.NAMOBJ || 'Unknown'}<br>
                                Lat: ${e.latlng.lat.toFixed(6)}<br>
                                Lng: ${e.latlng.lng.toFixed(6)}
                            </div>
                        `).openPopup();
                        
                        // Auto close popup after 3 seconds
                        setTimeout(() => {
                            if (window.tempMarker) {
                                window.tempMarker.closePopup();
                            }
                        }, 3000);
                    }
                });
            },
            style: style_ADMINISTRASIKECAMATAN_AR_25K_1_0,
        });
        bounds_group.addLayer(layer_ADMINISTRASIKECAMATAN_AR_25K_1);
        map.addLayer(layer_ADMINISTRASIKECAMATAN_AR_25K_1);
        function pop_JALAN_LN_25K_2(feature, layer) {
            var popupContent = '<table>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['NAMRJL'] !== null ? autolinker.link(String(feature.properties['NAMRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['KONRJL'] !== null ? autolinker.link(String(feature.properties['KONRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['MATRJL'] !== null ? autolinker.link(String(feature.properties['MATRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['FGSRJL'] !== null ? autolinker.link(String(feature.properties['FGSRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['UTKRJL'] !== null ? autolinker.link(String(feature.properties['UTKRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['TOLRJL'] !== null ? autolinker.link(String(feature.properties['TOLRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['WLYRJL'] !== null ? autolinker.link(String(feature.properties['WLYRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['AUTRJL'] !== null ? autolinker.link(String(feature.properties['AUTRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['KLSRJL'] !== null ? autolinker.link(String(feature.properties['KLSRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['SPCRJL'] !== null ? autolinker.link(String(feature.properties['SPCRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['JPARJL'] !== null ? autolinker.link(String(feature.properties['JPARJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['ARHRJL'] !== null ? autolinker.link(String(feature.properties['ARHRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['STARJL'] !== null ? autolinker.link(String(feature.properties['STARJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['KLLRJL'] !== null ? autolinker.link(String(feature.properties['KLLRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['MEDRJL'] !== null ? autolinker.link(String(feature.properties['MEDRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['LOCRJL'] !== null ? autolinker.link(String(feature.properties['LOCRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['JARRJL'] !== null ? autolinker.link(String(feature.properties['JARRJL']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['FCODE'] !== null ? autolinker.link(String(feature.properties['FCODE']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['REMARK'] !== null ? autolinker.link(String(feature.properties['REMARK']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['SRS_ID'] !== null ? autolinker.link(String(feature.properties['SRS_ID']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['LCODE'] !== null ? autolinker.link(String(feature.properties['LCODE']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['METADATA'] !== null ? autolinker.link(String(feature.properties['METADATA']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <td colspan="2">' + (feature.properties['SHAPE_Leng'] !== null ? autolinker.link(String(feature.properties['SHAPE_Leng']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                </table>';
            var content = removeEmptyRowsFromPopupContent(popupContent, feature);
			layer.on('popupopen', function(e) {
				addClassToPopupIfMedia(content, e.popup);
			});
			layer.bindPopup(content, { maxHeight: 400 });
        }

        function style_JALAN_LN_25K_2_0() {
            return {
                pane: 'pane_JALAN_LN_25K_2',
                opacity: 1,
                color: 'rgba(243,166,178,1.0)',
                dashArray: '',
                lineCap: 'square',
                lineJoin: 'bevel',
                weight: 1.0,
                fillOpacity: 0,
                interactive: true,
            }
        }
        map.createPane('pane_JALAN_LN_25K_2');
        map.getPane('pane_JALAN_LN_25K_2').style.zIndex = 402;
        map.getPane('pane_JALAN_LN_25K_2').style['mix-blend-mode'] = 'normal';
        var layer_JALAN_LN_25K_2 = new L.geoJson(json_JALAN_LN_25K_2, {
            attribution: '',
            interactive: true,
            dataVar: 'json_JALAN_LN_25K_2',
            layerName: 'layer_JALAN_LN_25K_2',
            pane: 'pane_JALAN_LN_25K_2',
            onEachFeature: pop_JALAN_LN_25K_2,
            style: style_JALAN_LN_25K_2_0,
        });
        bounds_group.addLayer(layer_JALAN_LN_25K_2);
        map.addLayer(layer_JALAN_LN_25K_2);
        function pop_hotel_3(feature, layer) {
            var popupContent = '<table>\
                    <tr>\
                        <th scope="row">id</th>\
                        <td>' + (feature.properties['id'] !== null ? autolinker.link(String(feature.properties['id']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">nama</th>\
                        <td>' + (feature.properties['nama'] !== null ? autolinker.link(String(feature.properties['nama']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">alamat</th>\
                        <td>' + (feature.properties['alamat'] !== null ? autolinker.link(String(feature.properties['alamat']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">kecamatan</th>\
                        <td>' + (feature.properties['kecamatan'] !== null ? autolinker.link(String(feature.properties['kecamatan']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">latitude</th>\
                        <td>' + (feature.properties['latitude'] !== null ? autolinker.link(String(feature.properties['latitude']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">longitude</th>\
                        <td>' + (feature.properties['longitude'] !== null ? autolinker.link(String(feature.properties['longitude']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                    <tr>\
                        <th scope="row">kategori</th>\
                        <td>' + (feature.properties['kategori'] !== null ? autolinker.link(String(feature.properties['kategori']).replace(/'/g, '\'').toLocaleString()) : '') + '</td>\
                    </tr>\
                </table>';
            var content = removeEmptyRowsFromPopupContent(popupContent, feature);
			layer.on('popupopen', function(e) {
				addClassToPopupIfMedia(content, e.popup);
			});
			layer.bindPopup(content, { maxHeight: 400 });
        }

        function style_hotel_3_0() {
            return {
                pane: 'pane_hotel_3',
        rotationAngle: 0.0,
        rotationOrigin: 'center center',
        icon: L.icon({
            iconUrl: 'markers/hotel_3.svg',
            iconSize: [30.4, 30.4]
        }),
                interactive: true,
            }
        }
        map.createPane('pane_hotel_3');
        map.getPane('pane_hotel_3').style.zIndex = 403;
        map.getPane('pane_hotel_3').style['mix-blend-mode'] = 'normal';
        var layer_hotel_3 = new L.geoJson(json_hotel_3, {
            attribution: '',
            interactive: true,
            dataVar: 'json_hotel_3',
            layerName: 'layer_hotel_3',
            pane: 'pane_hotel_3',
            onEachFeature: pop_hotel_3,
            pointToLayer: function (feature, latlng) {
                var context = {
                    feature: feature,
                    variables: {}
                };
                return L.marker(latlng, style_hotel_3_0(feature));
            },
        });
        bounds_group.addLayer(layer_hotel_3);
        map.addLayer(layer_hotel_3);
        setBounds();

        // Add click handler for iframe communication (for areas outside polygons)
        map.on('click', function(e) {
            // Check if we're in an iframe
            if (window.parent && window.parent !== window) {
                // Send click coordinates to parent window
                window.parent.postMessage({
                    type: 'mapClick',
                    lat: e.latlng.lat,
                    lng: e.latlng.lng,
                    kecamatan: '' // Empty for areas outside polygons
                }, '*');
                
                // Add a temporary marker to show where clicked
                if (window.tempMarker) {
                    map.removeLayer(window.tempMarker);
                }
                
                window.tempMarker = L.marker([e.latlng.lat, e.latlng.lng], {
                    icon: L.icon({
                        iconUrl: 'markers/hotel_3.svg',
                        iconSize: [30, 30],
                        iconAnchor: [15, 15],
                        popupAnchor: [0, -15]
                    })
                }).addTo(map);
                
                window.tempMarker.bindPopup(`
                    <div style="text-align: center;">
                        <strong>Lokasi Hotel Baru</strong><br>
                        Lat: ${e.latlng.lat.toFixed(6)}<br>
                        Lng: ${e.latlng.lng.toFixed(6)}
                    </div>
                `).openPopup();
                
                // Auto close popup after 3 seconds
                setTimeout(() => {
                    if (window.tempMarker) {
                        window.tempMarker.closePopup();
                    }
                }, 3000);
            }
        });

        // Add custom cursor style when in iframe
        if (window.parent && window.parent !== window) {
            map.getContainer().style.cursor = 'crosshair';
            
            // Add instruction overlay
            var instructionControl = L.control({position: 'topright'});
            instructionControl.onAdd = function(map) {
                var div = L.DomUtil.create('div', 'leaflet-control-custom');
                div.innerHTML = `
                    <div style="background: rgba(0,123,255,0.9); color: white; padding: 8px 12px; 
                                border-radius: 5px; font-size: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                        <i class="fas fa-mouse-pointer"></i> Klik untuk pilih lokasi hotel
                    </div>
                `;
                return div;
            };
            instructionControl.addTo(map);
        }
        </script>
    </body>
</html>
