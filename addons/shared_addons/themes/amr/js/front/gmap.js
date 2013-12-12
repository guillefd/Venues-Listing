var markers = [];
var iterator = 0;    
var map;
var gimage;
var gimage_hover;

$(document).ready(function(){

    // Enable the visual refresh
    google.maps.visualRefresh = true;

    //map data params
    var gmapData = $.parseJSON(amrMapData);

    function initialize() 
    {
        //map data 
        var mapOptions = {
            zoom: gmapData.center.zoom,
            center: new google.maps.LatLng(gmapData.center.lat, gmapData.center.lng),
            //UI
            disableDefaultUI: true,
            mapTypeControl: false,
            zoomControl: true,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
              },    
            mapTypeControlOptions: {
              style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
            },                      
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);          

        for (var i = 0; i < gmapData.result.length; i++) 
        {
            setTimeout(function() {
                //Add marker to map
                addMapMarkers();
            }, i * 100);            
        }
    }    

    function addMapMarkers()
    {
        gimage = gmapData.markerImgUrl;
        gimage_hover = gmapData.markerImgUrl_hover;
        var item = gmapData.result[iterator];
        var posChecked = check_position(item);
        var cloudimgUri = gmapData.cloudimgUrl;        
        //infowindow        
        var contentString = '<a href="">'+
        '<div id="bodyContent">' +
            '<img src="'+ cloudimgUri + item.cloud_th_images[0] + '" class="img-responsive">' +        
            '<div class="footerContent"><h4 id="firstHeading" class="firstHeading">' + 
            item.space_denomination + ' ' + item.space_name +       
            '<span class="pull-right"><i class="fa fa-users"></i> ' +
            item.space_max_capacity + '</span>' +
            '</h4>' +                   
            '<p><i class="fa fa-map-marker"></i> ' + item.loc_area +
            ' <span class="pull-right"><i class="fa fa-home"></i> ' + item.loc_type + '</span></p>' +  
            '<p>Dirección: ' + item.loc_geo_street_name + 
            ' ' + roundNumber(item.loc_geo_street_number) +  '</p></div>' +             
        '</div>' +
        '</a>'; 
        infobox = new InfoBox({
            alignBottom: true,
            maxWidth: 400,
            disableAutoPan: false,
            pixelOffset: new google.maps.Size(-134, -35),
            boxStyle: {
               background: "#fff",
               width: "208px"
             },            
            closeBoxMargin: "-14px -10px 2px 2px",
            infoBoxClearance: new google.maps.Size(1, 80),
            pane: "floatPane",
            enableEventPropagation: false            
        });
        //marker 
        var marker = new google.maps.Marker({
            draggable: false,
            animation: google.maps.Animation.DROP,            
            position: posChecked,
            icon: gimage,
            map:map
        });   
        markers.push(marker);                            
        google.maps.event.addListener(marker, 'click', function() {
            infobox.setContent(contentString);
            infobox.open(map,marker);
            map.panTo(posChecked);
        });
        iterator++;         
    } 

    function check_position(item)
    {  
        pos2 = new google.maps.LatLng(Number(item.loc_lat), Number(item.loc_lng));        
        for(var j = 0; j < markers.length; j++)
        {
            pos1 = markers[j].getPosition();
            distance = google.maps.geometry.spherical.computeDistanceBetween(pos1, pos2);
            if(distance < 10)
            {
                rand = Math.floor((Math.random()*-180)+180);
                pos2 = google.maps.geometry.spherical.computeOffset(pos2, 25, rand);
            }
        }
        return pos2;        
    }

    function roundNumber(number)
    {
        digits = number.length;
        if(digits>1)
        {
            times = Math.pow(10, digits - 1);
            return Math.round(number / times) * times;            
        }
        else
            {
                return 10;
            }
    }

    //load map
    google.maps.event.addDomListener(window, 'load', initialize);

});