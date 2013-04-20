$(document).ready(function(){

    $(document).ajaxStart(function() {
        popup("Подождите...<br /><br /><img src='/images/preloader.gif' alt='wait please' /><br /><br /><br /><br /> ");
    });
    $(document).ajaxStop(function() {
        popup_close();
    });

});

function popup(message,auto) {
    var popup=$("#popup");
    $("#popup-shadow").show();
    popup.html(message);
    popup.show("fast");
    popup.focus();
    (auto)?setTimeout( function() { popup_close() } , 3000):"";
}
function popup_close() {
    var popup=$("#popup");
    popup.hide('fast');
    $("#popup-shadow").hide();
    popup.html("");
}
$(document).ready(function(){
    $("#popup-shadow").on("click",function () {
        popup_close();
    });
});