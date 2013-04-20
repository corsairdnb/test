$(document).ready(function() {

    function resize () {
        var form=$(".create");
        var list=$("#list");
        var offset=list.offset();
        form.css("left",list.width()+offset.left+2);
    }
    resize();
    $(window).resize(function(){
        resize();
    });

    $(window).scroll(function () {
        var form=$(".create");
        var list=$("#list");
        var offset=list.offset();
        if ($(window).scrollTop() > offset.top-10) {
            if (!form.hasClass("fixed")) {
                form.addClass("fixed");
            }
        } else {
            if (form.hasClass("fixed")) {
                form.removeClass("fixed");
            }
        }
    });

});