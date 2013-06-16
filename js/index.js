$(function(){

    /* Submit keycode */
    $("#auth").on("keypress",function(e){
        if (e.which == 13) {
            $("#auth-submit").click();
            return false;
        }
    });
    $("#auth-submit").on("click",function(){
        var key=$("#auth-key").val();
        var test=$("#auth-test option:selected").val();
        if (key=="" || key==undefined) {
            popup("Вы не ввели ключ!",true);
            $("#auth-key").focus();
            return;
        } else if (key.length!=8) {
            popup("Ключ должен состоять из 8 символов!",true);
            $("#auth-key").focus();
            return;
        }
        else {
            var data={key: key, test: test};
            $.ajax({
                url: "/ajax/check_keycode.php",
                type: "POST",
                dataType: "text",
                data: data,
                success: function(msg){
                    if (msg=="true") {
                        var params="menubar=no,location=no,resizable=no,scrollbars=yes,status=no";
                        var url = "/test.php";
                        window.open(url, "Система тестирования", params);
                        $("#auth").hide('fast');
                        $("#shadow").hide();
                    } else {
                        alert("Неверный код");
                        $("#auth-key").focus();
                    }
                },
                error: function(msg){
                    alert("Произошла ошибка! Попробуйте снова.");
                }
            });
        }
    });

    $("#auth-test option").on("click",function() {
        $("#auth-key").focus();
    });

    /* Open authorization window */
    $("#test-control").on("click",function () {
        $("#auth").show();
        $("#shadow").show();
        $("#auth-test").focus();
    });
    $("#auth-cancel").on("click",function () {
        $("#auth").hide('fast');
        $("#shadow").hide();
    });
    $("#shadow").on("click",function () {
        $("#auth").hide('fast');
        $("#shadow").hide();
    });

});