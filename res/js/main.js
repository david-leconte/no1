$(function() {
    $('input[name="last-seen-msg"]').val(Math.floor(Date.now() / 1000));
    App.colorFromUsername();

    $('#search .reload').click(function(event) {
        event.preventDefault();
        App.reload();
    });

    $("#search").on('keypress', function(event) {
        if(event.which == 13) {
            event.preventDefault();

            App.sendSearch($(this).serialize());
        }
    });

    $('#create').submit(function(event) {
        event.preventDefault();
        
        $.post('index.php', $(this).serialize()).done(() => {
            App.reload();
            $("#create #text").val("");
        });
    });

    let callScroll = true;
    $(window).scroll(function(event) {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 100 && callScroll) {
            //console.log("Bottom reached !");
            callScroll = false;
            App.loadNewMessages(event);

            setTimeout(function() {  callScroll = true; }, 500);
        }
     });
});