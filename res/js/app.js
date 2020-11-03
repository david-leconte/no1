// Attributes messages color depending on the username (actually depends on the first char of the username)
class App {
    static loadMoreAllowed = true;

    static colorFromUsername(username) {
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

    static formatNew(timestamp, data) {
        //console.log(data);
        $.each(data, function (index, element) {
            let message = $(
                '<article>' +
                '<div class="sticker"></div>' +
                '<button class="author"></button>' +

                '<p class="right-infos">' +
                '<i class="message-datetime"></i>' +
                '<button class="delete"><i class="fas fa-trash"></i></button>' +
                '</p>' +

                '<p class="text"></p>' +

                '<button class="upvote"><i class="fas fa-chevron-up"></i></button>' +
                '<button class="downvote"><i class="fas fa-chevron-down"></i></button>' +

                '<a href="">Copy link</a>' +
                '</article>');

            message.find(".author").html(element.username);
            message.find(".text").html(element.text);
            message.find(".message-datetime").html(element.datetime);
            message.find("a").attr("href", "?message=" + element.id);

            $("main").append(message);
        });

        this.colorFromUsername();
    }

    static reload() {
        this.loadMoreAllowed = true;

        $("article").each(function () {
            $(this).remove();
            //console.log("removed");
        });

        let timestamp = Math.floor(Date.now() / 1000);
        $('#search input[name="last-seen-msg"]').val(timestamp);

        $.post(
            "index.php",
            {
                "json": 1,
                "last-seen-msg": timestamp
            },
            "json"
        ).done(function (data) {
            App.formatNew(timestamp, data);
        });
    }

    static loadNewMessages() {
        if(this.loadMoreAllowed) {
            let dateString = $("article").last().find(".message-datetime").html();

            console.log(dateString);

            let dateTimeParts = dateString.split(' '),
                timeParts = dateTimeParts[1].split(':'),
                dateParts = dateTimeParts[0].split('/');

            let date = new Date(dateParts[2], parseInt(dateParts[0], 10) - 1, dateParts[1], timeParts[0], timeParts[1], timeParts[2]);
            let timestamp = Math.floor(date.getTime() / 1000);

            console.log(date);
            //timestamp = $('#search input[name="last-seen-msg"]').val();
            $('#search input[name="last-seen-msg"]').val(timestamp);

            $.post(
                "index.php",
                {
                    "json": 1,
                    "last-seen-msg": timestamp
                },
                "json"
            ).done(function (data) {
                App.formatNew(timestamp, data);
            });
        }
    }

    static sendSearch(params) {
        $("article").each(function () {
            $(this).remove();
            //console.log("removed");
        });

        let timestamp = Math.floor(Date.now() / 1000);
        $('#search input[name="last-seen-msg"]').val(timestamp);

        console.log(params);

        $.post(
            "index.php", params, "json"
        ).done(function (data) {
            App.formatNew(timestamp, data);
        });

        this.loadMoreAllowed = false;
    }
}




