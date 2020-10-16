var datatableArtistas = null;
var token = $('[name="_token"]').val();
var idActual = null;
var idArtista = null;

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
    cargarPais();
    listadoArtistas();
    $("#newArtista").on("click", function() {
        $("#nombreArtista").val("");
        $("#selectPais").val(0);
        $("#base64Img").val("");
        $("#artistaImagen").attr("src", "");
        $("#imgArtista").val("");
        $("#divImagen").addClass("oculto");
        $("#actualizarArtista").addClass("oculto");
        $("#registrarArtista").removeClass("oculto");
        $("#modalAdminArtista").modal("show");
    });
    $("#registrarArtista").on("click", function() {
        crearArtista();
    });
    $("#actualizarArtista").on("click", function() {
        artistaEditar();
    });
    $('#imgArtista').on('change', loadimage);

}

function cargarPais() {
    spinner();
    $.ajax({
        url: "/Artistas/cargarPais",
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
                codigo = objeto.id_pais;
                nombre = objeto.nombre;
                var option = $("<option/>");
                option.val(codigo);
                option.html(nombre);
                $("#selectPais").append(option);
            });
        }
    });

}

function listadoArtistas() {
    spinner();
    if (datatableArtistas === null) {
        datatableArtistas = $("#tableArtistas").DataTable({
            ajax: {
                /*  url: "/Usuarios/listar", */
                url: $('#tableArtistas').data('route'),
                header: { 'X-CSRF-TOKEN': token },
                type: "POST",
                data: function(d) {
                    d.accion = 1;
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
                            onclick: "eliminarArtista(\"" + full.DT_RowId + "\")"

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
                            onclick: "editarArtista(\"" + full.DT_RowId + "\")"
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

                        return full.id_artista;
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
                            class: "img-fluid img-thumbnail"

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
                        return full.pais;
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
        datatableArtistas.ajax.reload(null, false);
    }
}

function crearArtista() {
    var nombreArtista = $("#nombreArtista").val();
    var idPais = $("#selectPais").val();
    var imagefile = document.getElementsByName("single-image")[0].files;
    var errores = [];
    if (nombreArtista === "") {
        errores.push("- El nombre de artista es obligatorio");
    }
    if (parseInt(idPais) === 0) {
        errores.push("- El pais  de artista es obligatorio");
    }
    if (imagefile.length <= 0) {
        errores.push("- La imagen del artista es obligatoria");
    }
    console.log(errores);
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
        url: "/Artistas/AdminCrearArtista",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
            nombreArtista: nombreArtista,
            idPais: idPais,
            imageBase64Artista: $("#base64Img").val()
        },
        success: function(data) {
            spinner(false);
            if (data.success) {
                $("#modalAdminArtista").modal("hide");
                datatableArtistas.ajax.reload(null, false);
                Swal.fire(
                    'Creado!',
                    'Se ha Creado el artista correctamente !',
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

function eliminarArtista(id) {
    var data = datatableArtistas.row("#" + id).data();
    Swal.fire({
        title: 'Eliminar',
        text: 'Esta seguro de eliminar el artista ' + data.nombre + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, Mantener'
    }).then((result) => {
        if (result.value) {
            spinner();
            $.ajax({
                url: "/Artistas/AdminEliminarArtista",
                dataType: 'json',
                type: "POST",
                data: { _token: token, idArtista: data.id_artista },
                success: function(data) {
                    spinner(false);
                    datatableArtistas.ajax.reload(null, false);
                    Swal.fire(
                        'Eliminado!',
                        'Se ha eliminado el Artista correctamente !',
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

function editarArtista(id) {
    idArtista = id;
    $("#actualizarArtista").removeClass("oculto");
    $("#registrarArtista").addClass("oculto");
    var data = datatableArtistas.row("#" + id).data();
    $("#nombreArtista").val(data.nombre);
    $("#selectPais").val(data.id_pais);
    $("#base64Img").val(data.image);
    $("#artistaImagen").attr("src", data.image);
    $("#divImagen").removeClass("oculto");
    $("#imgArtista").val("");
    $("#modalAdminArtista").modal("show");
}

function artistaEditar() {
    var data = datatableArtistas.row("#" + idArtista).data();
    var nombreArtista = $("#nombreArtista").val();
    var selectPais = $("#selectPais").val();
    var imagenArtista = $("#base64Img").val();
    spinner();
    $.ajax({
        url: "/Artistas/AdminEditarArtista",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idArtista: idArtista, nombreArtista: nombreArtista, selectPais: selectPais, imagenArtista: imagenArtista },
        success: function(data) {
            spinner(false);
            $("#modalAdminArtista").modal("hide");
            datatableArtistas.ajax.reload(null, false);
            Swal.fire(
                'Editado!',
                'Se ha Editado el Artista correctamente !',
                'success'
            )
        }
    });

}

function loadimage() {
    if (this.files.length > 0) {
        var embedimage = this.files[0];
        if (embedimage.type.indexOf("image/") !== -1) {
            var reader = new FileReader();
            reader.onload = function() {
                var urlbase64 = reader.result;
                $("#base64Img").val(urlbase64);
                $("#artistaImagen").attr("src", urlbase64);
                $("#divImagen").removeClass("oculto");
            };
            reader.readAsDataURL(embedimage);
        }
    }
}