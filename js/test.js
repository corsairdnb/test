$(function(){

    var testTime = parameters["time"];
    $('#timer').countdown({
        until: testTime,
        onExpiry: timeIsOver,
        compact: true
    });

    function timeIsOver () {
        alert('Ваше время вышло!');
    }

    // build question list
    (function(){
        var q, i= 1,
            list = parameters["question_list"],
            navigation = $("#navigation");
        for (q in list) {
            $("<li/>",{
                html: i,
                "data-id": list[q]
            }).appendTo(navigation);
            i++;
        }
        setActive (0);
    })();

    function setActive (id) {
        var li = $("#navigation li"),
            number = $("#question-title span");
        li.removeClass("active");
        if (id == 0) {
            li.eq(0).addClass("active");
            number.html("1");
        }
        else {
            li.filter("[data-id="+id+"]").addClass("active");
            number.html(li.index()+2);
        }
    }

    function getQuestion () {
        $.ajax({
            url: "/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            data: makeJSON({
                action: "getQuestion",
                params: {
                    question_id: parameters["question"]
                }
            }),
            success: function(msg){
                $("#question").html(msg["content"][0]["text"]);
            },
            error: function(msg){

            }
        });
    }

    getQuestion ();

});