$(document).ready(function(){

    var testTime=1500;
    $('#timer').countdown({
        until: testTime,
        onExpiry: timeIsOver,
        compact: true
    });

    function timeIsOver () {
        alert('Ваше время вышло!');
    }

});