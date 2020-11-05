var token = $('[name="_token"]').val();


$(document).ready(function() {
    iniciar();
});

function spinner(texto) {
    if (texto === "") {
        texto = "Cargando...";
    }
    if (texto === false) {
        $("#spinner").hide();
        return;
    }
    $("#textLoad").html(texto);
    $("#spinner").show();
}

function iniciar() {
    $('#imgArtista').on('change', loadimage);
}

function loadimage() {
    if (this.files.length > 0) {
        var embedimage = this.files[0];
        if (embedimage.type.indexOf("image/") !== -1) {
            var reader = new FileReader();
            reader.onload = function() {
                var urlbase64 = reader.result;
                $("#base64Img").val(urlbase64);
                $("#image").attr("src", urlbase64);
                $("#divImagen").removeClass("oculto");
            };
            reader.readAsDataURL(embedimage);
        }
    }
}