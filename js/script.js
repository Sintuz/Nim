
function play(id) {
    console.log(id);
    $.post({
        url: 'index.php',
        data: {
            "id": id
        }
    },() => {
        location.reload();
    });
}