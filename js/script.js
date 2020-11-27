
function play(id) {
    console.log(id);
    $.post({
        url: 'play.php',
        data: {
            "id": id
        }
    },(response) => {
        location.reload();
    });
}