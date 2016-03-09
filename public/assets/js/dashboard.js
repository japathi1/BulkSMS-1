$(function(){
    //Example #3
    $("#count").characterCounter({
        increaseCounting: true,
        counterFormat: '%1 chars.'
    });
    $('#datetimepicker1').datetimepicker();
    $("#datetimepicker1").hide();
});

function fixLater() {
    var d = $("#datetimepicker1").data("DateTimePicker").date();
    //console.log(moment(d).unix());
    $("#later").val(moment(d).unix());
    return true;
}

function hide(id,h){
    if(h)
        $("#"+id).hide();
    else
        $("#"+id).show();
}