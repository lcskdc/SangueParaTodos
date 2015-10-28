$(function() {
    /*
    $("#banner").bjqs({
        width: 700,
        height: 264,
        animtype: 'fade',
        animduration: 1500, // how fast the animation are
        animspeed: 1500, // the delay between each slide
        automatic: true, // automatic
        showcontrols: false, // show next and prev controls
        centercontrols: false, // center controls verically
        showmarkers: false, // Show individual slide markers
        centermarkers: false // Center markers horizontally
    });
    */
    $("#banner").owlCarousel({

            navigation : false, // Show next and prev buttons
            slideSpeed : 300,
            paginationSpeed : 400,
            singleItem:true,
            autoPlay:true,
            stopOnHover : false

    });    
    google.maps.event.addDomListener(window,'load',criarMapa);
});