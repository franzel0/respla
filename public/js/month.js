$(document).ready(function(){

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

    $( "#date_from" ).datepicker({
        minDate: 0,
        onSelect: function (date) {
        	date = $(this).datepicker('getDate');
        	$('#date_to').datepicker('option', 'minDate', $(this).val());
        }
    });

    $( "#date_to" ).datepicker();

	$("#table-events tr").selectable({
	    filter: "td",
	    stop: function() {
	    	var events = []; //new Array();
	    	$(".ui-selected").each(function(){
				events.push($(this).data('reasonid'));
			});
			var approved = ($(".ui-selected:first").data("approved")=="approved" ? 1 : 0);
			$("#approve").prop('checked', approved);
	    	//alert("Genehmigt: "+ approved);
			// get comment of first element
			try {
    			var v = $(this).children(".ui-selected").eq(0).attr("title");
				var v2 = v.split("Bemerkung: ");
				$("#comment").val(v2[1]);
			}
			catch (err) {}

		   	$('#item_selection').modal();

	    }
    });


	$(".selected-event").on("click", function(){
		var date_from = $('.ui-selected:first').data('date');
		var date_to = $('.ui-selected:last').data('date');
		var entry_id = $(this).val();
		var tr = $('.ui-selected:first').parent();
		var user_id = tr.data('id');
		var event_id = []; //new Array();
		$( ".ui-selected" ).each(function( i ) {
			event_id.push($(this).data("event_id"));
		});
		var approved = ($('#approve').is(':checked') ? 1 :0 );

		$.ajax({
		    url: 'insertEvents',
		    type: "post",
		    data: {'sender': 'month',
		    	   'date_from': date_from,
		    	   'date_to': date_to,
		    	   'entry_id': entry_id,
		    	   'user_id': user_id,
		    	   'event_id': event_id,
		    	   'comment': $('#comment').val(),
		    	   'approved': approved,
		    	   '_token': $('#xtoken').text()
		    	   },
		    success: function(data){
				$('#item_selection').modal("hide");
				if (data == '2')
				{
					alert ("Keine Berechtigung");
				}
				else
				{
					//alert(data);
					tr.html(data);
				}
		    },
		    error: function(data){
		    	$('#item_selection').modal("hide");
		    	var a = JSON.parse(data.responseText);
      		  	alert(a.user_id);
      		}
    	});
    	$('#comment').val('');
		$('.ui-selected').removeClass('ui-selected');
	});

	$('#item_selection').on('hidden.bs.modal', function () {
		$('#comment').val('');
		$('.ui-selected').removeClass('ui-selected');
	});

	//save events from modal #save_events
	$(".save_events").on("click", function(){
		if(!parseDate($("#date_from").val()) || !parseDate($("#date_to").val()))
		{
			alert("Bitte Datum überprüfen!");
			return;
		}
		var user_id = $('#selectUser').val();
		var approved = ($('#approve2').is(':checked') ? 1 :0 );
		var entry_id = ($(this).val() === 0 || $(this).val() == -1 || $(this).val() == -2) ? $(this).val() : $('#entry').val();
		$.ajax({
		    url: 'modalinsertEvents',
		    type: "post",
		    data: {'date_from': $('#date_from').val(),
		    	   'date_to': $('#date_to').val(),
		    	   'entry_id': entry_id,
		    	   'user_id': user_id,
		    	   'comment': $('#comment2').val(),
		    	   'approved': approved,
		    	   '_token': $('#xtoken').text()
		    	   },
		    success: function(data){
				if (data == '2')
				{
					alert ("Keine Berechtigung");
				}
				else
				{ 	//alert(data);
					location.reload();
					//$("tr").find("[data-id='" + user_id + "']").html(data);
				}
		    },
		    error: function(data){
      		  	alert("Diese Einträge können nicht durch Sie geändert werden!");
      		}
    	});
    	$('#insert_events').modal('hide');
    	$('#comment2').val('');
	});

});
