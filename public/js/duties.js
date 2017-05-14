$(document).ready(function(){
	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
	
	function el_height(){

        var h = $( window ).height();
        try {
            var p = $("#left-bottom").offset();
			var height;
            if($(window).width()<768){
                height = h - p.top - 20 ;
            }
            else{
                height = h - p.top - 20 ;
            }
            $("#right-bottom").css("height", height);
            $("#left-bottom").css("height", height);
            $("#right-top").css("width", $("#right-bottom").width());
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#right-bottom").width()<=$("#table-events") .width()) {
                //$("#panel-body").height("-=18");
                $("#left-bottom").height("-=18");
            }
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#right-bottom").height()<=$(" #table-events" ).height()) {
                $("#right-top").width("-=16");
            }

            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
              $("#left-bottom").css("overflow-y","auto");
              $("#right-top").css("overflow-x","auto");
            }
            $("#hoehe").text(height);

        }
        catch(err){
            alert(err.message);
        }
    }

    /*
    *function to parse the date
    */
    function parseDate(str) {
  		//var m = str.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
  		var m = str.match(/^(0?[1-9]|[12][0-9]|3[01])[\.\-](0?[1-9]|1[012])[\.\-]\d{4}$/);
  		return (m) ? new Date(m[3], m[2]-1, m[1]) : null;
	}

    /*
    * Change height for element
    */
    el_height();

    /*
    * Recompute the height fo divs when changing the size of the window
    */
    $( window ).resize(function(){
      el_height();
    });
});
