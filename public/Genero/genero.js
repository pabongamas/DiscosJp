var datatableGeneros = null;
var token = $('[name="_token"]').val();
var idActual = null;
var idGenero = null;

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
    listadoGenero();
    $("#newGenero").on("click", function() {
        $("#nombreGenero").val("");
        $("#actualizarGenero").addClass("oculto");
        $("#registrarGenero").removeClass("oculto");
        $("#modalAdminGenero").modal("show");
    });
    $("#registrarGenero").on("click", function() {
        crearGenero();
    });
    $("#actualizarGenero").on("click", function() {
        generoEditar();
    });
}

function listadoGenero() {
    spinner();
    if (datatableGeneros === null) {
        datatableGeneros = $("#tableGeneros").DataTable({
            ajax: {
                /*  url: "/Usuarios/listar", */
                url: $('#tableGeneros').data('route'),
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
                            onclick: "eliminarGenero(\"" + full.DT_RowId + "\")"

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
                            onclick: "editarGenero(\"" + full.DT_RowId + "\")"
                        });
                        img.addClass("cursor-pointer icono-accion editar");
                        span.append(img);
                        return span.html();
                    }
                },
                {
                    data: '',
                    width: "10%",
                    class: "text-center w10",
                    render: function(data, type, full, meta) {
                        return full.id_genero;
                    }
                },
                {
                    data: '',
                    width: "80%",
                    class: "text-center w80",
                    render: function(data, type, full, meta) {
                        return full.nombre;
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
        datatableGeneros.ajax.reload(null, false);
    }
}

function crearGenero() {
    var nombreGenero = $("#nombreGenero").val();
    var errores = [];
    if (nombreGenero === "") {
        errores.push("- El nombre del genero es obligatorio");
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
        url: "/Generos/AdminCrearGenero",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
            nombreGenero: nombreGenero
        },
        success: function(data) {
            spinner(false);
            if (data.success) {
                $("#modalAdminGenero").modal("hide");
                datatableGeneros.ajax.reload(null, false);
                Swal.fire(
                    'Creado!',
                    'Se ha Creado el genero correctamente !',
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

function eliminarGenero(id) {
    var data = datatableGeneros.row("#" + id).data();
    Swal.fire({
        title: 'Eliminar',
        text: 'Esta seguro de eliminar el genero ' + data.nombre + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, Mantener'
    }).then((result) => {
        if (result.value) {
            spinner();
            $.ajax({
                url: "/Generos/AdminEliminarGenero",
                dataType: 'json',
                type: "POST",
                data: { _token: token, idGenero: data.id_genero },
                success: function(data) {
                    spinner(false);
                    if (data.usado) {
                        Swal.fire(
                            'Error!',
                            'No se puede eliminar el genero este esta siendo utilizado !',
                            'error'
                        )
                        datatableGeneros.ajax.reload(null, false);
                    } else {
                        datatableGeneros.ajax.reload(null, false);
                        Swal.fire(
                            'Eliminado!',
                            'Se ha eliminado el Genero correctamente !',
                            'success'
                        )
                    }
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

function editarGenero(id) {
    idGenero = id;
    $("#actualizarGenero").removeClass("oculto");
    $("#registrarGenero").addClass("oculto");
    var data = datatableGeneros.row("#" + id).data();
    $("#nombreGenero").val(data.nombre);
    $("#modalAdminGenero").modal("show");
}

function generoEditar() {
    var data = datatableGeneros.row("#" + idGenero).data();
    var nombreGenero = $("#nombreGenero").val();
    var errores = [];
    if (nombreGenero === "") {
        errores.push("- El nombre del genero es obligatorio");
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
        url: "/Generos/AdminEditarGenero",
        dataType: 'json',
        type: "POST",
        data: {
            _token: token,
            idGenero: idGenero,
            nombreGenero: nombreGenero,

        },
        success: function(data) {
            spinner(false);
            $("#modalAdminGenero").modal("hide");
            datatableGeneros.ajax.reload(null, false);
            Swal.fire(
                'Editado!',
                'Se ha Editado el Genero correctamente !',
                'success'
            )
        }
    });

}