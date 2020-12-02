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

function changeLang(el) {
    if (el.value === "IT") {
        console.log(el.value)
        $("#name-text").html("Scegli un nome");
        $("#language-text").html("Lingua");
        $("#difficulty-text").html("Difficoltá");
        $("#simple-text").html("Semplice");
        $("#medium-text").html("Medio");
        $("#hard-text").html("Difficile");
        $("#input-name").attr("placeholder", "Nome");
        $("#submit-button").html("Gioca");
    } else {
        $("#name-text").html("Choose a username");
        $("#language-text").html("Language");
        $("#difficulty-text").html("Difficulty");
        $("#simple-text").html("Simple");
        $("#medium-text").html("Medium");
        $("#hard-text").html("Hard");
        $("#input-name").attr("placeholder", "Username");
        $("#submit-button").html("Play");
    }
}