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

    $(document).on("click","#navigation li",function(){
        setActive("",$(this));
        getQuestion ();
    });
    $(document).on("click","#next",function(){
        var next = $("#navigation li.active").next();
        if (next.length>0) {
            setActive("", next);
            getQuestion ();
        }
    });
    $(document).on("click","#prev",function(){
        var prev = $("#navigation li.active").prev();
        if (prev.length>0) {
            setActive("", prev);
            getQuestion ();
        }
    });
    $(document).on("click","#finish",function(){
        if (window.confirm("Вы уверены, что хотите завершить тест")) {
            $("#form").submit();
        }
    });
    $(document).on("click","#answer input",function(){
        var container = $("#form"),
            name = $(this).attr("name"),
            value = $(this).val();
        var input = $("#form input[name="+name+"]");
        if (input.length>0) {
            input.val(value);
        }
        else {
            $("<input>",{
                type: "hidden",
                name: name,
                value: value
            }).appendTo(container);
        }
    });
    $("#back").on("click",function(){
        window.close();
    });

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
        setActive (0,false);
    })();

    function setActive (id,obj) {
        var li = $("#navigation li"),
            number = $("#question-title span");
        if (obj==false) {
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
        else {
            li.removeClass("active");
            obj.addClass("active");
            number.html(obj.index()+1);
        }
    }

    function getActive () {
        return $("#navigation li.active").attr("data-id");
    }

    function getQuestion () {
        var id = getActive ();
        $.ajax({
            url: "/ajax/ajax.php",
            type: "POST",
            dataType: "json",
            data: makeJSON({
                action: "getQuestion",
                params: {
                    test_id: parameters["test_id"],
                    user_id: parameters["user_id"],
                    question_id: id
                }
            }),
            success: function(msg){
                setQuestion(msg);
            },
            error: function(msg){
                alert("ошибка");
            }
        });
    }

    function setQuestion(data) {
        $("#question").html(data["content"]["question"][0]["text"]);
        var answers = data["content"]["answer"];
        var qid = data["content"]["question"][0]['id'];
        if (answers) {
            var table = $("#answer table");
            table.html("")
            for (var answer in answers) {
                if (answers[answer]!=false) {
                    var text = answers[answer]["text"];
                    var id = answers[answer]["id"];
                    var input = "<tr><td><input type='radio' name='"+qid+"' value='"+id+"'></td> <td>"+text+"</td></tr>";
                    table.append(input);
                }
            }
        }
    }

    if (!$("body").hasClass("result")) getQuestion ();

});