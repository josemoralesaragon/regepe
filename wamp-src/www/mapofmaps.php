<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><title>Replay your GPS Tracks with REGEPE</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link type="text/css" rel="StyleSheet" href="styles/header.css" />
<link type="text/css" rel="StyleSheet" href="styles/inputform.css" />
</head>
<body>
<?php include('header.html'); ?>
    <div id="wrapper">
        <div id="body">
            <h2>Map of tracks</h2>
            <div id="loading">Loading... <img src="/images/loading.svg" width="32" height="32"/></div>
            <div id="map_of_maps" style="height:500px;width:500px;"></div>
        </div>
        <div id="footer_push"></div>
    </div>
<?php include('footer.html'); ?>
</body>
</html>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=${GMapsApiKey2}" type="text/javascript"></script>
<script src="javascript/xmlhttprequest.js" type="text/javascript"></script>
<script src="javascript/header.js" type="text/javascript"></script>
<script type="text/javascript">
//<![CDATA[

// create a clickable marker
function createMarker(pt, text) {
	var marker = new GMarker(pt, {icon: marker_icon});
	GEvent.addListener(marker, "click", function(){
		marker.openInfoWindowHtml(text);
	});
	return marker;
}

function onReadystatechange() {
	if (this.readyState == 4) {
		if (this.status == 200) {
            var maps = this.responseXML.getElementsByTagName("maps");
            for (i=0;i<maps.length;i++) {
                var lat = maps[i].getAttribute('lat');
                var lon = maps[i].getAttribute('lon');
                var mapslist = maps[i].getElementsByTagName("map");
                var mytext = '';
                for (j=0;j<mapslist.length;j++) {
                    var mapid = mapslist[j].getAttribute('mapid');
                    var mapdesc = mapslist[j].childNodes[0].nodeValue;
                    mytext += '<div><div style="max-width:300px;">' + mapdesc + '</div><div><img src="/thumbnail.php?mapid='+mapid+'" width="130" height="110"/> <A HREF="/showmap-flot.php?mapid=' + mapid + '">Go</A></div></div>';
                }
                var marker = createMarker(new GLatLng(lat,lon),mytext);
                map.addOverlay(marker);
                document.getElementById("loading").innerHTML = '';
            }
        }
    }
}

function fillMap() {
    var query_str = "/cgi-bin/getmaplist.py?all=yes";
    var client = new XMLHttpRequest();
    client.open('GET',query_str);
    client.send();
    client.onreadystatechange = onReadystatechange;
}

// Create map
var map = new GMap2(document.getElementById("map_of_maps"));
map.addMapType(G_PHYSICAL_MAP);
map.setMapType(G_PHYSICAL_MAP);
map.enableScrollWheelZoom();

map.setCenter(new GLatLng(45.0,0.0));
map.setZoom(5);

var marker_icon_size = new GIcon();
marker_icon_size.iconSize = new GSize(12, 20);
marker_icon_size.shadowSize = new GSize(22, 20);
marker_icon_size.iconAnchor = new GPoint(6, 20);
marker_icon_size.infoWindowAnchor = new GPoint(5, 1);
var marker_icon = new GIcon(marker_icon_size, "/images/MarkerSelEnd.png", null, "/images/MarkerShade.png");

fillMap();

//]]>
</script>
