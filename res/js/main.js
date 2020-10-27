// Attributes messages color depending on the username (actually depends on the first char of the username)
function colorFromUsername(username) {
    $("article").each(function() {
        let username = $(this).find(".author").html();

        let firstCharCode = username.charCodeAt(0);
        let color;

        if(firstCharCode >= 48 && firstCharCode <= 57) color = 'blue';
        else if(firstCharCode >= 65 && firstCharCode <= 77) color = 'green';
        else if(firstCharCode >= 78 && firstCharCode <= 90) color = 'red';
        else if(firstCharCode >= 97 && firstCharCode <= 109) color = 'purple';
        else if(firstCharCode >= 110 && firstCharCode <= 122) color = 'orange';

        $(this).addClass(color);
        $(this).find(".sticker").addClass(color);
    });

}

$(function() {
    colorFromUsername();

    $('#search .reload').click(function(e) {
        e.preventDefault();

        let timestamp = Math.floor(Date.now() / 1000);
        $('#search input[name="last-seen-msg"]').value = timestamp;

        $.post(
             "index.php",
            {
                "json" : 1,
                "last-seen-msg" : timestamp
            },
            "json"
        ).done(function(data) {
            console.log(data);
            colorFromUsername();
        });
    });
});