
function play(id) {
    console.log(id);
    $.post({
        url: 'index.php',
        data: {
            "id": id
        }
    },(response) => {
        location.reload();
    });
}