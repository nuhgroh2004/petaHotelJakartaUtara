var wms_layers = [];


        var lyr_OpenStreetMap_0 = new ol.layer.Tile({
            'title': 'OpenStreetMap',
            'opacity': 1.000000,
            
            
            source: new ol.source.XYZ({
            attributions: ' ',
                url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png'
            })
        });
var format_Refactored_1 = new ol.format.GeoJSON();
var features_Refactored_1 = format_Refactored_1.readFeatures(json_Refactored_1, 
            {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
var jsonSource_Refactored_1 = new ol.source.Vector({
    attributions: ' ',
});
jsonSource_Refactored_1.addFeatures(features_Refactored_1);
var lyr_Refactored_1 = new ol.layer.Vector({
                declutter: false,
                source:jsonSource_Refactored_1, 
                style: style_Refactored_1,
                popuplayertitle: 'Refactored',
                interactive: true,
    title: 'Refactored<br />\
    <img src="styles/legend/Refactored_1_0.png" /> Cilincing<br />\
    <img src="styles/legend/Refactored_1_1.png" /> Kelapa Gading<br />\
    <img src="styles/legend/Refactored_1_2.png" /> Koja<br />\
    <img src="styles/legend/Refactored_1_3.png" /> Pademangan<br />\
    <img src="styles/legend/Refactored_1_4.png" /> Penjaringan<br />\
    <img src="styles/legend/Refactored_1_5.png" /> Tanjung Priok<br />\
    <img src="styles/legend/Refactored_1_6.png" /> <br />' });

lyr_OpenStreetMap_0.setVisible(true);lyr_Refactored_1.setVisible(true);
var layersList = [lyr_OpenStreetMap_0,lyr_Refactored_1];
lyr_Refactored_1.set('fieldAliases', {'WADMKK': 'WADMKK', 'WADMPR': 'WADMPR', 'SHAPE_Leng': 'SHAPE_Leng', 'SHAPE_Area': 'SHAPE_Area', 'Kecamatan': 'Kecamatan', 'Jumlah Hotel ': 'Jumlah Hotel ', });
lyr_Refactored_1.set('fieldImages', {'WADMKK': 'TextEdit', 'WADMPR': 'TextEdit', 'SHAPE_Leng': 'TextEdit', 'SHAPE_Area': 'TextEdit', 'Kecamatan': 'TextEdit', 'Jumlah Hotel ': 'TextEdit', });
lyr_Refactored_1.set('fieldLabels', {'WADMKK': 'hidden field', 'WADMPR': 'hidden field', 'SHAPE_Leng': 'hidden field', 'SHAPE_Area': 'hidden field', 'Kecamatan': 'inline label - always visible', 'Jumlah Hotel ': 'inline label - always visible', });
lyr_Refactored_1.on('precompose', function(evt) {
    evt.context.globalCompositeOperation = 'normal';
});