function play(id) {
    console.log(id);
    $.post({
        url: 'index.php',
        data: {
            "id": id
        }
    }, () => {
        location.reload();
    });
}

$(document).ready(function () {
    $("body").on("contextmenu onselectstart oncut oncopy onpaste ondrag", (e) => {
        e.preventDefault();
        return false;
    });
});
