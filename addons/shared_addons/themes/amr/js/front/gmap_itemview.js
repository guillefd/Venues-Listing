//global vars
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

    if(showmap == 1)
    {
        setTimeout(initialize, 500);       
    }

    $('#btnMap').on("click", function(){
        if(typeof map == 'undefined')
        {
            setTimeout(initialize, 500);
        }
    });       

    function initialize() 
    {
        //map data 
        var mapOptions = {
            zoom: 15,
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
        map = new google.maps.Map(document.getElementById('itemMap-canvas'), mapOptions);          

        for (var i = 0; i < gmapData.result.length; i++) 
        {
            setTimeout(function() {
                //Add marker to map
                addMapMarkers();
            }, i * 100);            
        }

        google.maps.event.trigger(map, 'resize');        
    }    

    function addMapMarkers()
    {
        gimage = gmapData.markerImgUrl;
        gimage_hover = gmapData.markerImgUrl_hover;
        var item = gmapData.result[iterator];
        var posChecked = check_position(item);
        var cloudimgUri = gmapData.cloudimgUrl;        

        //marker 
        var marker = new google.maps.Marker({
            draggable: false,
            animation: google.maps.Animation.DROP,            
            position: posChecked,
            icon: gimage,
            map:map
        });   
        markers.push(marker);                            

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
    //google.maps.event.addDomListener(window, 'load', initialize);


});