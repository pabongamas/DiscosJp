var datatableAlbums = null;
var dataTableCanciones = null;
var token = $('[name="_token"]').val();
var idActual = null;
var idAlbum = null;
var datosCanciones = null;
var idMaximo = 0;
var idAlbumActual = null;

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
    cargarArtistasGenero();
    listadoAlbums();
    $("#newAlbum").on("click", function() {
        $("#nombreAlbum").val("");
        $("#selectArtista").val(0);
        $("#base64Img").val("");
        $("#albumImagen").attr("src", "");
        $("#imgAlbum").val("");
        $("#anio").val("");
        $("#selectGenero").val(0);
        $("#divImagen").addClass("oculto");
        $("#actualizarAlbum").addClass("oculto");
        $("#registrarAlbum").removeClass("oculto");
        $("#modalAdminAlbums .modal-header").attr({
            style: ""
        });
        $("#modalAdminAlbums .modal-content").attr({
            style: ""
        })
        $("#modalAdminAlbums").modal("show");
    });
    $("#registrarAlbum").on("click", function() {
        crearAlbum();
    });
    $("#actualizarAlbum").on("click", function() {
        albumEditar();
    });
    $('#imgAlbum').on('change', loadimage);
    $("#agregarCancion").click(function() {
        var dataCancion = dataTableCanciones.rows().data().toArray();
        if (dataCancion.length > 0) {
            idMaximo = dataCancion.length;
        }
        var datosCanciones = {
            numero_cancion: idMaximo * 1 + 1,
            name: '',
            minutos: '',
            DT_RowId: idMaximo * 1 + 1
        };
        idMaximo = idMaximo * 1 + 1;
        dataTableCanciones.rows.add([datosCanciones]).draw();
    });

    $("#guardarCanciones").click(function() {
        guardarCanciones();
    });

}


function cargarArtistasGenero() {
    spinner();
    $.ajax({
        url: "/Albums/cargarArtistas",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
        },
        success: function(data) {
            spinner(false);
            var codigo;
            var nombre;
            $(data.data).each(function(indice, objeto) {
                codigo = objeto.id_artista;
                nombre = objeto.nombre;
                var option = $("<option/>");
                option.val(codigo);
                option.html(nombre);
                $("#selectArtista").append(option);
            });
            var idGenero;
            var nombreGenero;
            $(data.genero).each(function(indice, objeto) {
                idGenero = objeto.id_genero;
                nombreGenero = objeto.nombre;
                var option = $("<option/>");
                option.val(idGenero);
                option.html(nombreGenero);
                $("#selectGenero").append(option);
            });
        }
    });

}

function listadoAlbums() {
    spinner();
    if (datatableAlbums === null) {
        datatableAlbums = $("#tableAlbums").DataTable({
            ajax: {
                /*  url: "/Usuarios/listar", */
                url: $('#tableAlbums').data('route'),
                header: { 'X-CSRF-TOKEN': token },
                type: "POST",
                data: function(d) {
                    d._token = token;
                },
                dataSrc: "data"
            },
            drawCallback: function(settings) {
                spinner(false);
            },
            serverSide: true,
            ordering: false,
            /* searching: true, */
            processing: true,
            dom: '<"fondoGris"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3 lengthTop"l>>>tr<"fondoGrisAbajo"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3">>>',
            autoWidth: false,
            columns: [{
                    data: '',
                    width: "5%",
                    class: "text-center w5",
                    render: function(data, type, full, meta) {
                        var span = $("<span/>");
                        var img2 = $("<i/>");
                        img2.attr({
                            id: "eli_" + full.DT_RowId,
                            class: "fas fa-user-times",
                            title: "Eliminar",
                            style: "width: 18px;cursor:pointer",
                            onclick: "eliminarAlbum(\"" + full.DT_RowId + "\")"

                        });
                        img2.addClass("cursor-pointer icono-accion eliminar");
                        span.append(img2);
                        return span.html();
                    }
                },
                {
                    data: '',
                    width: "5%",
                    class: "text-center w5",
                    render: function(data, type, full, meta) {
                        var span = $("<span/>");
                        var img = $("<i/>");
                        img.attr({
                            id: "mod_" + full.DT_RowId,
                            class: "fas fa-pencil-alt",
                            title: "Editar",
                            style: "width: 20px;cursor:pointer",
                            onclick: "editarAlbum(\"" + full.DT_RowId + "\")"
                        });
                        img.addClass("cursor-pointer icono-accion editar");
                        span.append(img);
                        return span.html();
                    }
                },
                {
                    data: '',
                    width: "5%",
                    class: "text-center w10",
                    render: function(data, type, full, meta) {

                        return full.id_album;
                    }
                },
                {
                    data: '',
                    width: "5%",
                    class: "text-center w10",
                    render: function(data, type, full, meta) {
                        return full.nombre;
                    }
                },
                {
                    data: '',
                    width: "5%",
                    class: "text-center w10",
                    render: function(data, type, full, meta) {
                        var span = $("<span/>");
                        var div = $("<div/>");
                        div.attr({
                            id: "DivimgArtist_" + full.DT_RowId,

                        });
                        var img = $("<img/>");
                        img.attr({
                            id: "imgArtist_" + full.DT_RowId,
                            src: full.image,
                            class: "img-fluid img-thumbnail",
                            onclick: "verAlbum(" + full.DT_RowId + "," + full.id_artista + ")"

                        });

                        div.addClass("cursorPointer");
                        div.append(img);
                        span.append(div);
                        return span.html();
                    }
                },
                {
                    data: '',
                    width: "5%",
                    class: "text-center w10",
                    render: function(data, type, full, meta) {
                        return full.artista;
                    }
                }


            ],
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "<label style='margin:5px 5px 0 5px;'>Ver</label>" + '<select style="width:45px;height: 25px;">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="50">50</option>' +
                    '</select>',
                sZeroRecord: "No se encontraron resultados",
                sEmptyTable: "No se encontraron registros",
                sInfo: "_TOTAL_ registros encontrados",
                sInfoEmpty: "No se encontraron registros",
                sInfoFiltered: "(Filtrado de un total de _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Buscar:",
                sUrl: "",
                sInfoThousands: ",",
                sLoadingRecords: "Cargando...",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Ãšltimo",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
                oAria: {
                    sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sSortDescending: ": Activar para ordenar la columna de manera descendente"
                }
            },
            lengthMenu: [
                [10, 20, 50],
                [10, 20, 50]
            ]
        });
    } else {
        datatableAlbums.ajax.reload(null, false);
    }
}

function crearAlbum() {
    var nombreAlbum = $("#nombreAlbum").val();
    var idArtista = $("#selectArtista").val();
    var anio = $("#anio").val();
    var genero = $("#selectGenero").val();
    var imagefile = document.getElementsByName("single-image")[0].files;
    var errores = [];
    if (nombreAlbum === "") {
        errores.push("- El nombre del album es obligatorio");
    }
    if (parseInt(idArtista) === 0) {
        errores.push("- El artista es obligatorio");
    }
    if (parseInt(genero) === 0) {
        errores.push("- El genero es obligatorio");
    }
    if (anio === "") {
        errores.push("- El año del album es obligatorio");
    }
    if (imagefile.length <= 0) {
        errores.push("- La imagen del album es obligatoria");
    }
    if (errores.length > 0) {
        Swal.fire(
            'Error',
            "El registro no se pudo realizar. Se detectaron los siguientes errores:<br/><br/>" + errores.join("<br/>"),
            'error'
        )
        return;
    }
    spinner();
    $.ajax({
        url: "/Albums/AdminCrearAlbum",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
            nombreAlbum: nombreAlbum,
            idArtista: idArtista,
            imageBase64Album: $("#base64Img").val(),
            anio: anio,
            idGenero: genero
        },
        success: function(data) {
            spinner(false);
            if (data.success) {
                $("#modalAdminAlbums").modal("hide");
                datatableAlbums.ajax.reload(null, false);
                Swal.fire(
                    'Creado!',
                    'Se ha Creado el album correctamente !',
                    'success'
                )
            } else {
                Swal.fire(
                    'Error!',
                    data.msg,
                    'error'
                )
            }

        }
    });
}

function eliminarAlbum(id) {
    var data = datatableAlbums.row("#" + id).data();
    Swal.fire({
        title: 'Eliminar',
        text: 'Esta seguro de eliminar el album ' + data.nombre + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, Mantener'
    }).then((result) => {
        if (result.value) {
            spinner();
            $.ajax({
                url: "/Albums/AdminEliminarAlbum",
                dataType: 'json',
                type: "POST",
                data: { _token: token, idAlbum: data.id_album, idArtista: data.id_artista },
                success: function(data) {
                    spinner(false);
                    datatableAlbums.ajax.reload(null, false);
                    Swal.fire(
                        'Eliminado!',
                        'Se ha eliminado el Album correctamente !',
                        'success'
                    )
                }
            });

        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire(
                'Cancelado',
                '',
                'error'
            )
        }
    })
}

function editarAlbum(id) {
    idAlbum = id;
    $("#actualizarAlbum").removeClass("oculto");
    $("#registrarAlbum").addClass("oculto");
    var data = datatableAlbums.row("#" + id).data();
    $("#nombreAlbum").val(data.nombre);
    $("#selectArtista").val(data.id_artista);
    $("#base64Img").val(data.image);
    $("#anio").val(data.anio);
    $("#selectGenero").val(data.id_genero);
    $("#albumImagen").attr("src", data.image);
    $("#divImagen").removeClass("oculto");
    $("#imgAlbum").val("");
    $("#modalAdminAlbums .modal-header").attr({
        style: "background-color:" + data.color
    });
    $("#modalAdminAlbums .modal-content").attr({
        style: "border: 1px solid " + data.color + ";box-shadow: 5px 5px 8px 0px " + data.color + ";"
    })
    $("#modalAdminAlbums").modal("show");
}

function albumEditar() {
    var data = datatableAlbums.row("#" + idAlbum).data();
    var nombreAlbum = $("#nombreAlbum").val();
    var selectArtista = $("#selectArtista").val();
    var imagenAlbum = $("#base64Img").val();
    var anio = $("#anio").val();
    var selectGenero = $("#selectGenero").val();
    spinner();
    $.ajax({
        url: "/Albums/AdminEditarAlbum",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
            idAlbum: idAlbum,
            nombreAlbum: nombreAlbum,
            selectArtista: selectArtista,
            imagenAlbum: imagenAlbum,
            idArtista: data.id_artista,
            anio: anio,
            selectGenero: selectGenero
        },
        success: function(data) {
            spinner(false);
            $("#modalAdminAlbums").modal("hide");
            datatableAlbums.ajax.reload(null, false);
            Swal.fire(
                'Editado!',
                'Se ha Editado el Album correctamente !',
                'success'
            )
        }
    });

}

function verAlbum(idAlbum, idArtista) {
    idAlbumActual = idAlbum;
    datosCanciones = {};
    idMaximo = 0;
    $("#modalInfoAlbumSongs").modal("show");
    spinner();
    $.ajax({
        url: "/Albums/verInfoAlbum",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idAlbum: idAlbum, idArtista: idArtista },
        success: function(data) {
            $("#imgInfoAlbum").attr("src", data.album.image);
            $("#nombreAlbumLabel").html(data.album.name);
            $("#nombreArtista").html(data.album.artista);
            $("#generoAño").html(data.album.genero + " · <span>" + data.album.anio + "</span>");
            $("#modalInfoAlbumSongs .modal-header").attr({
                style: "background-color:" + data.album.color
            });
            $("#modalInfoAlbumSongs .modal-content").attr({
                style: "border: 1px solid " + data.album.color + ";box-shadow: 5px 5px 8px 0px " + data.album.color + ";"
            })
            $("#nombreArtista").css({
                "color": data.album.color
            })
            $("#nombreAlbumLabel").css({
                "color": data.album.color
            })
            $("#agregarCancion").css({
                "background-color": data.album.color
            })
            if (data.canciones.length > 0) {
                console.log("entro");
                crearTablaCanciones(data.canciones);
            } else {
                console.log("mas");
                crearTablaCanciones(datosCanciones);
            }

            spinner(false);
        }
    });
}
/* metodo para ccrear canciones */
function crearTablaCanciones(data) {
    if (dataTableCanciones === null) {
        dataTableCanciones = $("#tableAddCanciones").DataTable({
            data: data,
            columns: [{
                    data: "",
                    width: "10%",
                    class: "text-center bdright",
                    render: function(data, type, full, meta) {
                        return full.numero_cancion;
                    }
                },
                {
                    data: "",
                    width: "80%",
                    class: "text-left bdright ",
                    render: function(data, type, full, meta) {
                        /* return full.name; */
                        var input = $("<input/>").attr({
                            class: "form-control",
                            id: "artista_" + full.numero_cancion,
                            value: full.name
                        });
                        return $('<span/>').append(input).html();
                    }
                },
                {
                    data: "",
                    width: "10%",
                    class: "text-right bdright ",
                    render: function(data, type, full, meta) {
                        var div = $("<div/>").attr({
                            class: "form-group"
                        });
                        var label = $("<label/>").attr({
                            for: "minutos_" + full.numero_cancion
                        });
                        var labelSegundos = $("<label/>").attr({
                            for: "segundos_" + full.numero_cancion
                        });
                        var minutos = $("<input/>").attr({
                            id: "minutos_" + full.numero_cancion,
                            type: "range",
                            class: "form-control-range",
                            min: "0",
                            max: "60",
                            onchange: "cargarTextMinutos(this.value)"
                        });
                        var segundos = $("<input/>").attr({
                            id: "segundos_" + full.numero_cancion,
                            type: "range",
                            class: "form-control-range",
                            min: "0",
                            max: "60",
                            onchange: "cargarTextSegundos(this.value)"
                        });
                        if (full.id !== undefined) {
                            console.log(full.id);
                            console.log(full.minutos);
                            var splitMinutos = full.minutos.split(":");
                            minutos.val(splitMinutos[0]);
                            segundos.val(splitMinutos[1]);
                        } else {
                            console.log(full.id);
                        }

                        label.html("Minutos");
                        labelSegundos.html("Segundos");
                        div.append(label, minutos, labelSegundos, segundos);
                        return $('<span/>').append(div).html();
                    }
                }
            ],
            drawCallback: function(settings) {
                /*  var data = settings.oInit.data; */
                $(".editar").off();
                $(".editar").mouseover(function() {
                    $(this).attr("src", "../../iconos/EditarHover.png");
                }).mouseout(function() {
                    $(this).attr("src", "../../iconos/editar.png");
                });
                $(".eliminar").off();
                $(".eliminar").mouseover(function() {
                    $(this).attr("src", "../../iconos/basuraHover.png");
                }).mouseout(function() {
                    $(this).attr("src", "../../iconos/basura.png");
                });
                $("#tableCancionesXalbum tbody .dataTables_empty").html("No se encontraron canciones registradas");
                console.log(data);
                $(data).each(function(indice, objeto) {
                    if (objeto.id === undefined) {
                        console.log(objeto);
                    } else {
                        console.log(objeto.minutos);
                        var splitMinutos = objeto.minutos.split(":");
                        $("#minutos_" + objeto.numero_cancion).val(splitMinutos[0]);
                        $("#segundos_" + objeto.numero_cancion).val(splitMinutos[1]);
                    }
                });
            },
            serverSide: false,
            ordering: false,
            destroy: true,
            processing: false,
            pageLength: 50,
            dom: '',
            autoWidth: false,
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "<label style='margin:5px 5px 0 5px;'>Ver</label>" + '<select style="width:45px;height: 25px;">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="30">50</option>' +
                    '</select>',
                sZeroRecord: "No se encontraron resultados",
                sEmptyTable: "No se encontraron registros",
                sInfo: "_TOTAL_ registros encontrados",
                sInfoEmpty: "No se encontraron registros",
                sInfoFiltered: "(Filtrado de un total de _MAX_ registros)",
                sInfoPostFix: "",
                sSearch: "Buscar:",
                sUrl: "",
                sInfoThousands: ",",
                sLoadingRecords: "Cargando...",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Ãšltimo",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                },
                oAria: {
                    sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sSortDescending: ": Activar para ordenar la columna de manera descendente"
                }
            },
            lengthMenu: [
                [10, 20, 50],
                [10, 20, 50]
            ]
        });
    } else {
        dataTableCanciones.clear();
        console.log(data);
        dataTableCanciones.rows.add(data);
        dataTableCanciones.draw();

    }
}
/* metodo para visualizar minutos  y segundos*/
function cargarTextMinutos(minutos) {
    console.log(minutos);
}

function cargarTextSegundos(segundos) {
    console.log(segundos);
}

/* metodo para guardar las canciones  */
function guardarCanciones() {
    var data = dataTableCanciones.rows().data().toArray();
    $(data).each(function(indice, objeto) {
        var indiceActual = indice + 1;
        objeto.name = $("#artista_" + indiceActual).val();
        var minutos = $("#minutos_" + indiceActual).val();
        var segundos = $("#segundos_" + indiceActual).val();
        objeto.minutos = minutos + ":" + segundos;
    });
    /* var dataActual = dataTableCanciones.rows().data().toArray(); */
    spinner();
    $.ajax({
        url: "/Albums/guardarCanciones",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idAlbumActual: idAlbumActual, canciones: JSON.stringify(data) },
        success: function(data) {
            spinner(false);
            dataTableCanciones.clear();
            $("#modalInfoAlbumSongs").modal("hide");
            Swal.fire(
                'Guardado!',
                'Se han creado las canciones correctamente !',
                'success'
            )
        }
    });

}

/* metodo para cargar imagen base64 */
function loadimage() {
    if (this.files.length > 0) {
        var embedimage = this.files[0];
        if (embedimage.type.indexOf("image/") !== -1) {
            var reader = new FileReader();
            reader.onload = function() {
                var urlbase64 = reader.result;
                $("#base64Img").val(urlbase64);
                $("#albumImagen").attr("src", urlbase64);
                $("#divImagen").removeClass("oculto");
            };
            reader.readAsDataURL(embedimage);
        }
    }
}