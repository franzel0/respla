$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
 
    function el_height(){
        //inactivated as drag & drop does not work anymore with this function activated       
        var h = $( window ).height();
        try {
            var p = $(".plan-panel-body").offset();
            var height = h - p.top - 90 ;
            
            $(".plan-overflow").css("height", height);

            /*$(".plan-overflow").slimScroll({
                color: '#00f',
                size: '20px',
                alwaysVisible: true,
                height: height
            });

            $(".plan-overflow").slimScroll();*/
            
            $("#hoehe").text(height);
        }
        catch(err){
            alert(err.message);
        }
    }

    function attachEvents(el)
    {
        el.draggable({
            helper: function () {
                        return jQuery(this).clone().appendTo('body').css({
                        'zIndex': 10
                    });
            },
            opacity: 0.7,
            start: function( event, ui ) {
                $("#user" + $(this).data("user")).fadeIn("slow");
                $("#stats" + $(this).data("user")).fadeIn("slow");
                var id = $(this).data("user");
                $("#events > tbody  > tr").each(function ( index, value ) {
                    $("#user" + id + " > tbody > tr").eq(index+1).css('height', $(this).css('height'));
                });
            },
            stop: function( event, ui ) {
                $("#user" + $(this).data("user")).fadeOut(3000);
                $("#stats" + $(this).data("user")).fadeOut(3000);
            }
        });
        el.children().unbind("click");
        el.children("button").on("click", function(event){
            var ui_event = $(this).closest("span");
            var user = $(this).closest("span").data("user");
            var date = $(this).closest("tr").data("date");
            if (!confirm("Möchten Sie diesen Eintrag wirklich löschen?")) return;
            $.ajax({
                url: 'deletePlanEvent',
                type: "post",
                data: {'new_date': date,
                       'user_id' : user},
                success: function(html){
                    $("#user" + user).empty();
                    $("#user" + user).append(html);
                    $("#events > tbody  > tr").each(function ( index, value ) {
                        $("#user" + user + " > tbody > tr").eq(index).css('height', $(this).css('height'));
                    });
                    ui_event.remove();
                },
                error: function(html){
                    alert("Fehler!");
                }
            }); 
        })
    }

    $(".slimScrollBar").bind('move', function () {
        alert("hi");
        $(".userdata").scrollTop($(this).scrollTop());
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


    /*
    * Attach drag events und click event for delete button to selector
    */
    attachEvents($('#events .drag, #users .drag'));

    $("#hoehe").on("click", function(){
        alert($("#hoehe").text());
        $(".plan-overflow").slimScroll({
            height: $("#hoehe").text()
        });
    })

    $('.drop').droppable({
        accept: ".drag",
        drop: function (event, ui) {
            var droparea = $(this);
            var draggable = ui.draggable;
            var old_date = draggable.closest("tr").data("date");
            var new_date = $(this).closest("tr").data("date");
            var user = draggable.data("user");

            //check if user already exists in events table
            var user_exists = 0;
            $(this).children("span").each(function (event, ui) {
                if( $(this).data("user") == draggable.data("user") ){
                    user_exists = 1;
                } 
            });
            if (user_exists) {
                alert("Benutzer existiert bereits an dieser Stelle!");
                return;
            }

            //ShortText and clone element if not from events
            if($(draggable).closest("table").attr("id") == "events")
            {
                var element = draggable;
            }
            else
            {
                var element=$(draggable).clone();
                element.html(element.data("short") + ' <button class="btn btn-sm btn-info">x</button>');
            }
            
            //attach to droparea        
            $(this).append(element);
        
            //reattach drag-function to element
            attachEvents(element);
            //save dragged event
            
            $.ajax({
                url: 'insertPlanEvent',
                type: "post",
                data: {
                       'old_date': old_date,
                       'new_date': new_date, 
                       'entry_id': $("#entry").val(),
                       'user_id': user
                       },
                success: function(html){

                    if (html == "2")
                    {
                        alert("Keine Berechtigung");
                    }
                    else
                    { 
                        $("#user" + draggable.data("user")).empty();
                        $("#user" + draggable.data("user")).append(html);
                        $("#events > tbody  > tr").each(function ( index, value ) {
                            $("#user" + draggable.data("user") + " > tbody > tr").eq(index).css('height', $(this).css('height'));
                        });
                        /*update the stats*/
                        $.ajax({
                            url: 'updateStats',
                            type: "post",
                            data: {'new_date': new_date,
                                   'user_id' : user,
                                   'entry_id': $("#entry").val()
                                },
                            success: function(html){
                                $("#stats" + draggable.data("user")).empty();
                                $("#stats" + draggable.data("user")).append(html);
                            }
                        });
                    }
                },
                error: function(html){
                    // restore old situation
                    element.remove();
                    td.append(element);
                    attachEvents(element);
            
                    if(html.status == 401)
                    {
                        alert("Sie sind nicht berechtigt, diese Aktion auszuführen!");
                    }
                    else if (html.status ==501)
                    {
                        alert("Eintrag an einem Wochenende oder Feiertag nicht möglich!");
                    }
                    else
                    {
                        alert("Fehler!");
                    }
                }
            });   
        }
    });



})