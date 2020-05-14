;(function($){
  $( function() {
    let speed = oiiio_settings.speed;
    if( speed ){
      $( '#cbp-qtrotator' ).cbpQTRotator({
        interval : oiiio_settings.speed
      });
    }else{
      $( '#cbp-qtrotator' ).cbpQTRotator({
        interval : 8000
      });
    }
  });
})(jQuery);