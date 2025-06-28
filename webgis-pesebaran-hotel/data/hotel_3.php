<?php
    include "../../config/koneksi.php";
    $sql="select * from hotel where longitude IS NOT NULL AND latitude IS NOT NULL";
    $hasil=mysqli_query($conn,$sql);
    
    $features = array();
    while($data=mysqli_fetch_array($hasil)){
        $id=$data['id'];
        $nama=addslashes($data['nama']); // Escape quotes
        $alamat=addslashes($data['alamat']); // Escape quotes
        $kecamatan=$data['kecamatan'];
        $longitude=$data['longitude'];
        $latitude=$data['latitude'];
        $kategori=$data['kategori'];
        
        $features[] = array(
            "type" => "Feature",
            "properties" => array(
                "id" => $id,
                "nama" => $nama,
                "alamat" => $alamat,
                "kecamatan" => $kecamatan,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "kategori" => $kategori
            ),
            "geometry" => array(
                "type" => "Point",
                "coordinates" => array(floatval($longitude), floatval($latitude))
            )
        );
    }
    
    $geojson = array(
        "type" => "FeatureCollection",
        "name" => "hotel_3",
        "crs" => array(
            "type" => "name",
            "properties" => array("name" => "urn:ogc:def:crs:OGC:1.3:CRS84")
        ),
        "features" => $features
    );
    
    header('Content-Type: application/javascript');
    echo "var json_hotel_3 = " . json_encode($geojson) . ";";