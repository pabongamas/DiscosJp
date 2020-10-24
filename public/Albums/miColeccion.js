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
    miColeccionAlbums();
}

/* la siguiente funcion es para cargar la coleccion de albums que tenga un usuario  */

function miColeccionAlbums() {
    /* $("#rowAlbums").html("");
    $("#divArtistas").addClass("oculto");
    $("#divAlbums").removeClass("col-3").addClass("col-9", "blind");
    $("#divAlbums").removeClass("oculto");
    $("#divAlbumsxArtista").addClass("oculto");
    $("#divCanciones").addClass("oculto"); */
    /* spinner(); */
    $.ajax({
        url: "/Albums/miColeccion",
        dataType: 'json',
        type: "POST",
        data: { _token: token },
        success: function(data) {
            /*   $(data.data).each(function(i, o) {
                  var divCol = $("<div/>").addClass("col-6 col-lg-3 col-md-3");
                  var divCard = $("<div/>").addClass("card").attr({
                      "style": "width: 18rem;margin-bottom: 15px;box-shadow:5px 5px 8px 0px " + o.color + ";border: 1px solid " + o.color
                  });
                  var imgCard = $("<img/>").addClass("card-img-top cursorPointer").attr({
                      "src": o.image,
                      "alt": data.nombre,
                      "style": "    max-height: 274px",
                      "onclick": "verInfoAlbum(" + o.id_album + "," + o.id_artista + ")"
                  });
                  var divCardBody = $("<div/>").addClass("card-body");
                  var nombreAlbum = $("<span/>").addClass("card-text").html(o.nombre);
                  var nombreArtista = $("<p/>").addClass("card-text").html(o.artista);
                  divCardBody.append(nombreAlbum, nombreArtista);
                  if (data.userNoAdmin && !o.enColeccion) {
                      var CaretAñadir = $("<i/>").addClass("fas fa-plus cursorPointer").attr({
                          "style": "float:right;font-size:18px;color:" + o.color,
                          "data-toggle": "tooltip",
                          "data-placement": "right",
                          "title": "Añadir " + o.nombre + " a mi colección",
                          "onclick": "addAlbumColeccion(" + o.id_album + ")"
                      })
                      nombreArtista.append(CaretAñadir);
                  } else if (data.userNoAdmin && o.enColeccion) {
                      var CaretAñadir = $("<i/>").addClass("fas fa-check cursorPointer").attr({
                          "style": "float:right;font-size:18px;color:" + o.color,
                          "data-toggle": "tooltip",
                          "data-placement": "right",
                          "title": o.nombre + " ya en mi colección"
                      })
                      nombreArtista.append(CaretAñadir);
                  }
                  divCard.append(imgCard, divCardBody);
                  divCol.append(divCard);
                  $("#rowAlbums").append(divCol);
              });
              $('[data-toggle="tooltip"]').tooltip()
              spinner(false); */
            spinner(false);

        }
    });
}