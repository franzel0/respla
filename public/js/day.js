$(document).ready(function(){

	function el_height(){

        var h = window.innerHeight;
        try {
            var p = $("#panel-body").offset();
			var height;
            if($(window).width()<768){
                height = h - p.top - 170 ;
            }
            else{
                height = h - p.top - 180 ;
            }
            $("#details-list").css("height", height + 44);
            $("#users-list").css("height", height);
            $("#right-top").css("width", $("#details-list").width());
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#details-list").width()<=$("#table-events") .width()) {
                //$("#panel-body").height("-=18");
                $("#users-list").height("-=18");
            }
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#details-list").height()<=$(" #table-events" ).height()) {
                $("#right-top").width("-=16");
            }
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
              $("#users-list").css("overflow-y","auto");
              $("#right-top").css("overflow-x","auto");
            }
            $("#hoehe").text(height);

        }
        catch(err){
            alert(err.message);
        }
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

	var selectedId;
	var selectedEvent;
	var selectedRow;

    $( "#day" ).datepicker();

    $(".entry").on("click", function(){
    	selectedId = $(this).parent().data('id');
    	selectedRow = $(this).parent();
        var approved = ($(this).data("approved")=="approved" ? 1 : 0);
        $("#approve").prop('checked', approved);
    	$('#item_selection').modal();
    });

    $(".selected-event").on("click", function(){
		$('#item_selection').modal("hide");
        var approved = ($('#approve').is(':checked') ? 1 :0 );
		$.ajax({
		    url: 'insertEvents',
		    type: "post",
		    data: {'sender': 'day',
		    	   'date_from': $('#day').val(),
		    	   'date_to': $('#day').val(),
		    	   'entry_id': $(this).val(),
		    	   'user_id': selectedId,
		    	   'comment': $('#comment').val(),
                   'approved': approved,
		    	   '_token': $('#xtoken').text()
		    	   },
		    success: function(data){
				if (data == '2')
				{
					alert ("Keine Berechtigung");
				}
				else
				{
					selectedRow.html(data);
					selectedRow.children('.entry').on("click", function(){
    					selectedId = $(this).parent().data('id');
    					selectedEvent = $(this).data('eventid');
    					selectedRow = $(this).parent();
    					$('#item_selection').modal();
    				});
				}
		    },
		    error: function(data){
      		  	alert("Diese Einträge können nicht durch Sie geändert werden!");
      		}
    	});
    	$('#comment').val('');
		$('.ui-selected').removeClass('ui-selected');
	});

});
