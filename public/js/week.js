$(document).ready(function(){
 
	function el_height(){
                
        var h = $( window ).height();
        try {
            var p = $("#week-panel").offset();
            if($(window).width()<768){
                var height = h - p.top ;
            }
            else{
                var height = h - p.top - 330 ;
            }
            $(".week-overflow").css("height", height/2);
            $("#right-top").css("width", $("#right-bottom").width());
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#right-bottom").width()<=$("#table-events") .width()) {
                //$("#panel-body").height("-=18");
                $("#left-bottom").height("-=18");
            };
            if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && $("#right-bottom").height()<=$(" #table-events" ).height()) {
                $("#right-top").width("-=16");
            };
            ;
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

    $.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

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

    $(".btn-comment").on("click", function(){
    	var text = $(this).closest(".input-group").children("input").val();
    	//alert(text);
    	$.ajax({
		    url: 'insertcomment',
		    type: "post",
		    data: {
		    		id: $(this).val(),
		    		date: $(this).data('date'),
		    		text: text,
		    	   },
		    success: function(data){
		    	/*alert($(this).closest("th").attr('title'));
		    	$(this).closest(".input-group").children("input").attr('title', text);*/
		    },				
		    error: function(data){
      		  	alert("Fehler!");
      		}		
    	}); 
    });

    $(".entry").on("click", function(){
    	user_id = $(this).data('id');
    	selectedRow = $(this).parent();
    	date = $(this).data("date");
        var approved = ($(this).data("approved")=="approved" ? 1 : 0);
    	$('#item_selection').modal();
    });

    $(".selected-event").on("click", function(){
		$('#item_selection').modal("hide");
        var approved = ($('#approve').is(':checked') ? 1 :0 );
		$.ajax({
		    url: 'insertEvents',
		    type: "post",
		    data: {'sender': 'day',
		    	   'date_from': date, 
		    	   'date_to': date,
		    	   'entry_id': $(this).val(),
		    	   'user_id': user_id,
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
      		  		//alert("weiter");
      		  		$("#week-form").submit();
				}
		    },
		    error: function(data){
		    	alert("Diese Einträge können Sie nicht ändern!");
      		}		
    	}); 
    	$('#comment').val(''); 
		$('.ui-selected').removeClass('ui-selected');
	});


})