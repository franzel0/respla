$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //change the order oncalls
    function changeorder($sortable){
        var entries = [];
        var isvisible;
        entries = $sortable.children().map(function(){
            return  $(this).data("id");
        }).get();
        if ($sortable.attr('id') == "sortable2"){
            isvisible = 1;
        }
        else{
            isvisible = 0;
        }
        $.ajax({
            url: '/changeOncallOrder',
            type: "post",
            data: {
                'isvisible': isvisible,
                'entries': entries
            }
        });
    }

    $( "#sortable1, #sortable2" ).sortable({
        connectWith: ".connectedSortable",
        update: function(event, ui) {
            //changeorder($(this));
            var entries = [];
            var isvisible;
            //if #sortable2 take al li-elements, #sortable1 only moved item
            if ($(this).attr('id') == "sortable2"){
                isvisible = 1;
                entries = $(this).children().map(function(){
                    return  $(this).data("id");
                }).get();
            }
            else{
                isvisible = 0;
                entries.push($(ui.item).data("id"));
            }
            $.ajax({
                url: '/changeOncallOrder',
                type: "post",
                data: {
                    'isvisible': isvisible,
                    'entries': entries
                }
            });
        }
    });
    $("#resort").on("click", function(){
         location.reload();
    });
});
