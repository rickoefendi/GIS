<div class="content">
	<div id="map" style="width: 100%; height: 650px; color:black;"></div>
</div>
<script>

	var wisata = new L.LayerGroup();
	
	var map = L.map('map', {
		center: [-7.000, 113.333],
		zoom: 10,
		zoomControl: false,
		layers: []
	});
	var GoogleSatelliteHybrid = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
		maxZoom: 22,
		attribution: 'WebGIS Trainning by Roni Haryadi'
	}).addTo(map);

	var Esri_NatGeoWorldMap = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', {
		attribution: 'Tiles &copy; Esri &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC',
		maxZoom: 16
	});

	var GoogleRoads = new L.TileLayer('https://mt1.google.com/vt/lyrs=h&x={x}&y={y}&z={z}', {
		opacity: 1.0,
		attribution: 'WebGIS Trainning by Riko Efendy'
	});

	var GoogleMaps = new L.TileLayer('https://mt1.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
		opacity: 1.0,
		attribution: 'WebGIS Trainning by Roni Haryadi'
	});

	var baseLayers = {
		'Google Satellite Hybrid': GoogleSatelliteHybrid,
		'Esri National Geography': Esri_NatGeoWorldMap,
		'Google Maps': GoogleMaps,
		'Google Roads': GoogleRoads
	};

	var groupedOverlays = {
		"Peta Dasar": {
			'wisata': wisata,
		}
	};
	L.control.groupedLayers(baseLayers, groupedOverlays, {collapsed: true}).addTo(map);

	var osmUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
	var osmAttrib = 'Map data &copy; OpenStreetMap contributors';
	var osm2 = new L.TileLayer(osmUrl, {
		minZoom: 0,
		maxZoom: 13,
		attribution: osmAttrib
	});
	var rect1 = {
		color: "#ff1100",
		weight: 3
	};
	var rect2 = {
		color: "#0000AA",
		weight: 1,
		opacity: 0,
		fillOpacity: 0
	};
	var miniMap = new L.Control.MiniMap(osm2, {toggleDisplay: true,
		position: "bottomright",
		aimingRectOptions: rect1,
		shadowRectOptions: rect2
	}).addTo(map);
	L.control.coordinates({
		position: "bottomleft",
		decimals: 2,
		decimalSeperator: ",",
		labelTemplateLat: "Latitude: {y}",
		labelTemplateLng: "Longitude: {x}"
	}).addTo(map);
	L.Control.geocoder({
		position: "topleft",
		collapsed: true
	}).addTo(map);
	var north = L.control({
		position: "bottomleft"
	});
	north.onAdd = function(map) {
		var div = L.DomUtil.create("div", "info legend");
		div.innerHTML = '<img src="assets/a.png">';
		return div;
	}
	north.addTo(map);
	/* GPS enabled geolocation control set to follow the user's location */
	var locateControl = L.control.locate({
		position: "topleft",
		drawCircle: true,
		follow: true,
		setView: true,
		keepCurrentZoomLevel: true,
		markerStyle: {
			weight: 1,
			opacity: 0.8,
			fillOpacity: 0.8
		},
		circleStyle: {
			weight: 1,
			clickable: false
		},
		icon: "fa fa-location-arrow",
		metric: false,
		strings: {
			title: "My location",
			popup: "You are within {distance} {unit} from this point",
			outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
		},
		locateOptions: {
			maxZoom: 18,
			watch: true,
			enableHighAccuracy: true,
			maximumAge: 10000,
			timeout: 10000
		}
	}).addTo(map);
	var zoom_bar = new L.Control.ZoomBar({
		position: 'topleft'
	}).addTo(map);

	$.getJSON("assets/wisata.geojson", function(data) {
		var ratIcon = L.icon({
			iconUrl: 'assets/marker.png',
			iconSize: [12, 10]
		});
		L.geoJson(data, {
			pointToLayer: function(feature, latlng) {
				var marker = L.marker(latlng, {
					icon: ratIcon
				});
				marker.bindPopup(feature.properties.nama);
				return marker;
			}
		}).addTo(wisata);
	
	});
	

	const legend = L.control.Legend({
position: "bottomright",
collapsed: false,
symbolWidth: 24,
opacity: 1,
column: 2,
legends: [{
label: "wisata",
type: "image",
url:"assets/marker.png",
},{
}]
})
.addTo(map);
	
</script>