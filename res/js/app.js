/*
    App class
*/

class App {
    constructor() {
        this.loadMoreAllowed = true;
        this.firstLoad = true;

        this.messageTemplate = $(
        '<article>' +
            '<div class="sticker"></div>' +
            '<button class="author"></button>' +

            '<p class="right-infos">' +
                '<i class="message-datetime"></i>' +
                '<button class="delete" disabled><i class="fas fa-trash"></i></button>' +
            '</p>' +

            '<p class="text"></p>' +

            '<button class="upvote"><i class="fas fa-chevron-up"></i></button>' +
            '<button class="downvote"><i class="fas fa-chevron-down"></i></button>' +

            '<a href="">Copy link</a>' +
        '</article>');

        this.lastSeenMsg;
    }

    // Attributes messages color depending on the username (actually depends on the first char of the username)

    colorFromUsername(username) {
        $("article").each(function () {
            let username = $(this).find(".author").html();

            let firstCharCode = username.charCodeAt(0);
            let color;

            if (firstCharCode >= 48 && firstCharCode <= 57)
                color = 'blue';
            else if (firstCharCode >= 65 && firstCharCode <= 77)
                color = 'green';
            else if (firstCharCode >= 78 && firstCharCode <= 90)
                color = 'red';
            else if (firstCharCode >= 97 && firstCharCode <= 109)
                color = 'purple';
            else if (firstCharCode >= 110 && firstCharCode <= 122)
                color = 'orange';

            $(this).addClass(color);
            $(this).find(".sticker").addClass(color);
        });

    }

    formatNew(data) {
        //console.log(data);

        let app = this;

        let currentAuthor = $("#user-id").text();

        $.each(data, function (index, element) {
            let message = app.messageTemplate.clone();

            message.find(".author").html(element.username);

            if(element.username == currentAuthor) {
                message.find(".delete").css("opacity", "1");
                message.find(".delete").removeAttr("disabled");
            }

            message.find(".delete").val(element.msgID);

            message.find(".text").html(element.text);
            message.find(".message-datetime").html(element.datetime);
            message.find("a").attr("href", "?message=" + element.msgID);

            $("main").append(message);
            //console.log(message.find('.text').text());
        });

        this.colorFromUsername();
        this.loadEvents();
    }

    reload() {
        this.loadMoreAllowed = true;

        history.replaceState && history.replaceState(
            null, '', location.pathname + location.search.replace(/[\?&]message=[^&]+/, '').replace(/^&/, '?')
          );

        $("article").each(function () {
            $(this).remove();
            //console.log("removed");
        });

        let app = this;

        this.lastSeenMsg = Math.floor(Date.now() / 1000) + 5; // safer
        $("#last-seen").val(app.lastSeenMsg);

        $.post(
            "index.php",
            {
                "json": 1,
                "last-seen-msg": app.lastSeenMsg
            },
            "jsonp"
        ).done(function (data) {
            app.formatNew(data);
        });
    }

    loadMoreMessages() {
        if(this.loadMoreAllowed) {
            let dateString = $("article").last().find(".message-datetime").html();

            //console.log(dateString);

            let dateTimeParts = dateString.split(' '),
                timeParts = dateTimeParts[1].split(':'),
                dateParts = dateTimeParts[0].split('/');

            let date = new Date(dateParts[2], parseInt(dateParts[0], 10) - 1, dateParts[1], timeParts[0], timeParts[1], timeParts[2]);
            this.lastSeenMsg = Math.floor(date.getTime() / 1000);

            console.log(date);

            let app = this;
            $("#last-seen").val(app.lastSeenMsg);

            $.post(
                "index.php",
                {
                    "json": 1,
                    "last-seen-msg": app.lastSeenMsg
                },
                "jsonp"
            ).done(function (data) {
                console.log(data);
                app.formatNew(data);
            });
        }
    }

    sendSearch(params) {
        $("article").each(function () {
            $(this).remove();
            //console.log("removed");
        });

        //console.log(params);
        this.lastSeenMsg = Math.floor(Date.now() / 1000);
        let app = this;

        $.post(
            "index.php", params, "jsonp"
        ).done(function (data) {
            app.formatNew(data);
            console.log(params);
        });

        $("#last-seen").val(app.lastSeenMsg);

        this.loadMoreAllowed = false;
    }

    getMessageParam() {
        let app = this;

        var messageParam = null,
            getParam = [];

        location.search
            .substr(1)
            .split("&")
            .forEach(function (item) {
              getParam = item.split("=");
              if (getParam[0] === "message") messageParam = parseInt(getParam[1]);
        });

        if(!messageParam) this.reload();

        else {
            this.sendSearch("message=" + messageParam + "&last-seen-msg=" + this.lastSeenMsg + "&json=1");
        }
    }

    sendMessage(messageData) {
        let app = this;

        $.post('index.php', messageData, function(data) {
            app.reload();
            $("#create #text").val("");
        });
    }

    tryDeleteMessage(messageID) {
        messageID = parseInt(messageID);

        let app = this;

        $.post('index.php', { "delete": messageID }, function() {
            app.reload();
        });
    }

    loadEvents() {
        let app = this;

        $('#search .reload').click(function(event) {
            event.preventDefault();
            app.reload();
        });
    
        $("#search").on('keypress', function(event) {
            if(event.which == 13) {
                event.preventDefault();
    
                app.sendSearch($(this).serialize());
            }
        });
    
        $('#create').submit(function(event) {
            event.preventDefault();
            
            app.sendMessage($(this).serialize());
        });
    
        let callScroll = true;
    
        $(window).scroll(function(event) {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100 && callScroll) {
                console.log("Bottom reached !");
                callScroll = false;
                app.loadMoreMessages(event);
    
                setTimeout(function() {  callScroll = true; }, 500);
            }
         });
    
         $('.delete').click(function(event) {
            app.tryDeleteMessage($(this).val());
        });
    }
}


$(function() {
    var app = new App();

    app.getMessageParam();
    app.loadEvents();
});