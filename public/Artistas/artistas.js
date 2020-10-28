var datatableArtistas = null;
var dataTableCanciones = null;
var datatableCancionesGeneral = null;
var datatableGenero = null;
var token = $('[name="_token"]').val();
var idActual = null;

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
    $("#ListaAllArtistas").on("click", function() {
        listadoArtistas();
    });
    $("#listaAllAlbums").on("click", function() {
        listadoAlbums();
    });
    $("#listaAllCanciones").on("click", function() {
        listadoCanciones();
    });
    $("#listaAllGeneros").on("click", function() {
        listadoGeneros();
    });
    $('.ulHover li').on('click', function() {
        $('.ulHover .liSelected').removeClass('liSelected');
        $(this).addClass('liSelected');
    });
    $('#colapseDisc').on('click', function() {
        $('#divOpciones').toggleClass('activo');
        $('#colapseDisc').toggleClass('rotate');
        $('.colapsoRecords').toggleClass('activo');
        if ($('#colapsoRecords i').hasClass("rotate")) {} else {}
    });
    /* var globalWidth = $(document).width();
    if (globalWidth <= 1240) {
        $("#colapseDisc").click();
    }
    $(window).resize(function() {
        var globalWidth = $(document).width();
        var collapsed = $(".colapsoRecords").hasClass("activo");
        if (globalWidth <= 1240 && !collapsed) {
            $("#colapseDisc").click();
        } else if (globalWidth > 1240 && collapsed) {
            $("#colapseDisc").click();
        }
    }); */
}

function listadoArtistas() {
    spinner();
    $("#divArtistas").removeClass("oculto");
    $("#tableAllArtistas").removeClass("oculto");
    $("#divAlbums").removeClass("col-9").addClass("col-3", "blind");
    $("#divAlbums").addClass("oculto");
    $("#divAlbumsxArtista").addClass("oculto");
    $("#divCanciones").addClass("oculto");
    $("#divTableGeneros").addClass("oculto");
    $("#divTableArtistas").removeClass("oculto");
    if (datatableArtistas === null) {
        datatableArtistas = $("#tableAllArtistas").DataTable({
            ajax: {
                /*  url: "/Usuarios/listar", */
                url: $('#tableAllArtistas').data('route'),
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
                $('#tableAllArtistas tbody').on('click', 'tr', function() {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        datatableArtistas.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                        var idArtista = $(this).attr("id");
                        cargarInfoAlbum(idArtista);
                    }
                });
            },
            serverSide: true,
            ordering: false,
            processing: true,
            dom: '<"fondoGris"<"row"<"col-8 paginateTop paginateCenter"p><"col-4 lengthTop"l>>>tr<"fondoGrisAbajo"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3">>>',
            autoWidth: false,
            columns: [{
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
                        return full.nombre;
                    }
                },


            ],
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "<label style='margin:5px 5px 0 5px;'>Ver</label>" + '<select style="width:45px;height: 25px;">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="50">50</option>' +
                    '</select>',
                sZeroRecord: "Sin artistas",
                sEmptyTable: "Sin artistas",
                sInfo: "_TOTAL_ Artistas",
                sInfoEmpty: "No se encontraron Artistas",
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

function listadoAlbums() {
    $("#rowAlbums").html("");
    $("#divArtistas").addClass("oculto");
    $("#divAlbums").removeClass("col-3").addClass("col-9", "blind");
    $("#divAlbums").removeClass("oculto");
    $("#divAlbumsxArtista").addClass("oculto");
    $("#divCanciones").addClass("oculto");
    spinner();
    $.ajax({
        url: "/Albums/listar",
        dataType: 'json',
        type: "POST",
        data: { _token: token },
        success: function(data) {
            $(data.data).each(function(i, o) {
                var divCol = $("<div/>").addClass("col-6 col-lg-3 col-md-6");
                var divCard = $("<div/>").addClass("card").attr({
                    "style": "margin-bottom: 15px;box-shadow:5px 5px 8px 0px " + o.color + ";border: 1px solid " + o.color
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
                if (!o.enColeccion) {
                    var CaretAñadir = $("<i/>").addClass("fas fa-plus cursorPointer").attr({
                        "style": "float:right;font-size:18px;color:" + o.color,
                        "data-toggle": "tooltip",
                        "data-placement": "right",
                        "title": "Añadir " + o.nombre + " a mi colección",
                        "onclick": "addAlbumColeccion(" + o.id_album + ")"
                    })
                    nombreArtista.append(CaretAñadir);
                    /* divCardBody.append(CaretAñadir); */
                } else if (o.enColeccion) {
                    /* <i class="fas fa-check"></i> */
                    var CaretAñadir = $("<i/>").addClass("fas fa-check cursorPointer").attr({
                        "style": "float:right;font-size:18px;color:" + o.color,
                        "data-toggle": "tooltip",
                        "data-placement": "right",
                        "title": o.nombre + " ya en mi colección"
                            /* "onclick": "addAlbumColeccion(" + o.id_album + ")" */
                    })
                    nombreArtista.append(CaretAñadir);
                }
                divCard.append(imgCard, divCardBody);
                divCol.append(divCard);
                $("#rowAlbums").append(divCol);
            });
            $('[data-toggle="tooltip"]').tooltip()
            spinner(false);

        }
    });
}

function cargarInfoAlbum(idArtista) {
    $("#rowAlbumsxArtista").html("");
    $("#rowAlbumsxGenero").html("");
    $("#rowAlbumsxArtista").removeClass("oculto");
    $("#rowAlbumsxGenero").addClass("oculto");
    console.log(idArtista);
    spinner();
    $.ajax({
        url: "/Albums/listarAlbumxArtista",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idArtista: idArtista },
        success: function(data) {
            /* encabezado del nombre del artista */
            $("#divAlbumsxArtista").removeClass("oculto");
            var tableArtista = $("<table/>").addClass("table table-bordered tableAlbums");
            var theadArtista = $("<thead/>");
            var trArtista = $("<tr/>");
            var tdArtista = $("<td/>").attr({
                "style": "padding: 21px 50px;"
            });
            var nombreArtista = $("<label/>").html(data.artista).attr({
                "style": "font-size: 20px;font-weight: bold;display:block;"
            });
            var totalAlbum = $("<span/>").attr({
                "style": "font-size: 15px;"
            })
            if (data.totalAlbums > 0) {
                totalAlbum.html(data.totalAlbums + " álbumes");
            } else {
                totalAlbum.html("Sin álbumes registrados");
            }
            tdArtista.append(nombreArtista, totalAlbum);
            trArtista.append(tdArtista);
            theadArtista.append(trArtista);
            tableArtista.append(theadArtista);
            $("#rowAlbumsxArtista").append(tableArtista);
            /* tabla por cada uno de los albums */
            $(data.albums).each(function(i, o) {
                var tableAlbum = $("<table/>").addClass("table table-bordered tableAlbums").attr({
                    style: "background-color:" + o.color
                });
                var theadAlbum = $("<thead/>");
                var tbodyAlbum = $("<tbody/>");
                var trAlbum = $("<tr/>");
                var tdAlbum = $("<td/>").attr({
                    "style": "padding: 21px 50px;"
                });
                var divThead = $("<div/>").attr({
                    "style": "display:flex"
                });
                var img = $("<img/>");
                img.attr({
                    id: "imgAlbum_" + o.idAlbum,
                    src: o.imageAlbum,
                    class: "img-fluid",
                    style: " width: 10%;"

                });
                var nombreAlbum = $("<label/>").html(o.nameAlbum).attr({
                    "style": "font-size: 20px;font-weight: bold;display: flex;"
                });
                var generoAnio = $("<label/>").html(o.genero + " · <span>" + o.anio + "</span>").attr({
                    "style": "font-size: 20px;font-weight: bold;"
                });
                var divCol = $("<div/>").addClass("col-lg-8 col-8").attr("style", "display: block;");
                divCol.append(nombreAlbum, generoAnio)
                divThead.append(img, divCol);
                tdAlbum.append(divThead);
                trAlbum.append(tdAlbum);
                theadAlbum.append(trAlbum);
                tableAlbum.append(theadAlbum);
                var tableCanciones = $("<table/>").addClass("table tableAlbums table-hover");
                var theadCanciones = $("<thead/>");
                var tbodyCanciones = $("<tbody/>");
                var trCount = $("<tr/>");
                var tdCount = $("<td/>").html(o.canciones.length + " canciones").attr({
                    style: "width:100%;text-align:right;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                    colspan: "3"
                });
                trCount.append(tdCount);
                tbodyCanciones.append(trCount);
                $(o.canciones).each(function(indice, objeto) {
                    var trBody = $("<tr/>");
                    var tdNumeroCancion = $("<td/>").html(objeto.numero_cancion).attr({
                        style: "width:10%;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                        /* style: "width:10%", */

                    });
                    var tdNombreCancion = $("<td/>").html(objeto.name).attr({
                        style: "width:80%;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                        /* style: "width:80%;", */
                    });
                    var tdDuracion = $("<td/>").html(objeto.minutos).attr({
                        style: "width:10%;border: 1px solid " + o.color + ";border-left: none;border-right:none;text-align: right;",
                        /* style: "width:10%", */
                    });
                    trBody.append(tdNumeroCancion, tdNombreCancion, tdDuracion);
                    tbodyCanciones.append(trBody);
                });
                tableCanciones.append(tbodyCanciones);
                var divCanciones = $("<div/>").attr({
                    style: "width: 100%;padding-left: 15px;padding-right: 15px;"
                });
                divCanciones.append(tableCanciones);
                $("#rowAlbumsxArtista").append(tableAlbum, divCanciones);
            });
            spinner(false);
        }
    });
}

function verInfoAlbum(idAlbum, idArtista) {
    $("#modalInfoAlbum").modal("show");
    spinner();
    $.ajax({
        url: "/Albums/verInfoAlbum",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idAlbum: idAlbum, idArtista: idArtista },
        success: function(data) {
            $("#imgInfoAlbum").attr("src", data.album.image);
            $("#nombreAlbum").html(data.album.name);
            $("#nombreArtista").html(data.album.artista);
            $("#generoAño").html(data.album.genero + " · <span>" + data.album.anio + "</span>");
            $("#modalInfoAlbum .modal-header").attr({
                style: "background-color:" + data.album.color
            })
            $("#modalInfoAlbum .modal-content").attr({
                style: "border: 1px solid " + data.album.color + ";box-shadow: 5px 5px 8px 0px " + data.album.color + ";"
            })
            $("#nombreArtista").css({
                "color": data.album.color
            })
            $("#nombreAlbum").css({
                "color": data.album.color
            })
            crearTablaCanciones(data.canciones);
            spinner(false);

        }
    });

}
/* metodo para cargar las canciones asociadas a un album , en el modulo de albums  */
function crearTablaCanciones(data) {
    dataTableCanciones = $("#tableCancionesXalbum").DataTable({
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
                    return full.name;
                }
            },
            {
                data: "",
                width: "10%",
                class: "text-right bdright ",
                render: function(data, type, full, meta) {
                    return full.minutos;
                }
            }
        ],
        drawCallback: function(settings) {
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

        },
        serverSide: false,
        ordering: false,
        destroy: true,
        processing: false,
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
}
/* metodo para cargar las canciones de todos los artistas junto con la inforamacion artista,album ,genero */
function listadoCanciones() {
    spinner();
    $("#divArtistas").addClass("oculto");
    $("#divAlbums").addClass("oculto");
    $("#divAlbumsxArtista").addClass("oculto");
    $("#divCanciones").removeClass("oculto");
    if (datatableCancionesGeneral === null) {
        datatableCancionesGeneral = $("#tableAllCanciones").DataTable({
            ajax: {
                /*  url: "/Usuarios/listar", */
                url: $('#tableAllCanciones').data('route'),
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
                $('#tableAllArtistas tbody').on('click', 'tr', function() {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        datatableArtistas.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                        var idArtista = $(this).attr("id");
                        cargarInfoAlbum(idArtista);
                    }
                });
            },
            serverSide: true,
            ordering: false,
            processing: true,
            dom: '<"fondoGris"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3 lengthTop"l>>>tr<"fondoGrisAbajo"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3">>>',
            autoWidth: false,
            columns: [{
                    data: '',
                    width: "5%",
                    class: "text-center",
                    render: function(data, type, full, meta) {
                        return full.numeroCancion;
                    }
                },
                {
                    data: '',
                    width: "15%",
                    class: "text-left",
                    render: function(data, type, full, meta) {
                        return full.nameCancion;
                    }
                },
                {
                    data: '',
                    width: "10%",
                    class: "text-right",
                    render: function(data, type, full, meta) {
                        return full.duracion;
                    }
                },
                {
                    data: '',
                    width: "15%",
                    class: "text-left",
                    render: function(data, type, full, meta) {
                        var span = "<span class='underline cursorPointer' onclick='cargarAlbumsArtistaxCancion(" + full.idArtista + ")'>" + full.artista + "</span>"
                        return span;
                        /* cargarAlbumsArtistaxCancion */
                    }
                },
                {
                    data: '',
                    width: "15%",
                    class: "text-left",
                    render: function(data, type, full, meta) {
                        var span = "<span class='underline cursorPointer' onclick='verInfoAlbum(" + full.idAlbum + "," + full.idArtista + ")'>" + full.album + "</span>"
                        return span;
                    }
                },
                {
                    data: '',
                    width: "15%",
                    class: "text-left",
                    render: function(data, type, full, meta) {
                        return full.genero;
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
        datatableCancionesGeneral.ajax.reload(null, false);
    }
}
/* funcion para cargar la informacion de un artista (albums y canciones ) en la interfaz f */
function cargarAlbumsArtistaxCancion(idArtista) {
    $("#bodyModalArtista").html("");
    spinner();
    $.ajax({
        url: "/Albums/listarAlbumxArtista",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idArtista: idArtista },
        success: function(data) {
            var colors = [];
            $(data.albums).each(function(indice, objeto) {
                var div = $("<div/>").addClass("container row").attr({
                    "style": "padding-bottom: 20px;"
                });
                var divColImg = $("<div/>").addClass("col-12 col-lg-6 col-md-12");
                var cardImg = $("<div/>").addClass("card").attr({
                    "style": "width: 22rem;margin-bottom: 15px;border: none !important;"
                });
                var Img = $("<img/>").addClass("card-img-top borderAlbum").attr({
                    "style": " max-height: 350px",
                    "src": objeto.imageAlbum
                });
                cardImg.append(Img);
                divColImg.append(cardImg);
                div.append(divColImg);
                var divColTable = $("<div/>").addClass("col-12 col-lg-6 col-md-12");
                var tableAlbum = $("<table/>").addClass("tableAlbums");
                var theadAlbum = $("<thead/>");
                var trHeadAlbum = $("<tr/>");
                var tdHeadAlbum = $("<td/>");
                var labelAlbum = $("<label/>").attr({
                    "id": "nombreAlbumArtista_" + indice,
                    "style": "font-size: 25px;font-weight: bold;display:block;"
                }).html(objeto.nameAlbum);
                var labelArtista = $("<label/>").attr({
                    "id": "nombreArtistaArtista_" + indice,
                    "style": "font-size: 20px;font-weight: bold;display:block;"
                }).html(data.artista);
                var labelGenero = $("<label/>").attr({
                    "id": "generoAñoArtista_" + indice,
                    "style": "font-size: 15px;display:block;"
                }).html(objeto.genero + " · <span>" + objeto.anio + "</span>");

                labelAlbum.css({
                    "color": objeto.color
                })
                labelArtista.css({
                    "color": objeto.color
                })
                colors.push(objeto.color);

                tdHeadAlbum.append(labelAlbum, labelArtista, labelGenero);
                trHeadAlbum.append(tdHeadAlbum);
                theadAlbum.append(trHeadAlbum);
                tableAlbum.append(theadAlbum);
                divColTable.append(tableAlbum);

                var tableAlbumbody = $("<table/>").addClass("tableAlbums").attr({
                    "id": "tableCancionesXalbum_" + indice
                });
                var tableAlbumbodyHead = $("<thead/>");
                var tableAlbumbodyTr = $("<tr/>");
                var tableAlbumbodyNume = $("<td/>").html("#");
                var tableAlbumbodyCanc = $("<td/>").html("Cancion");
                var tableAlbumbodyMinu = $("<td/>").html("Minutos");
                var tableBody = $("<tbody/>");
                if (objeto.canciones.length > 0) {
                    $(objeto.canciones).each(function(indice, objeto) {
                        var trBODY = $("<tr/>");
                        var tdNumeroCancion = $("<td/>").html(objeto.numero_cancion);
                        var tdCancion = $("<td/>").html(objeto.name);
                        var tdMinutos = $("<td/>").html(objeto.minutos);
                        trBODY.append(tdNumeroCancion, tdCancion, tdMinutos);
                        tableBody.append(trBODY);
                    });
                } else {
                    var trBODY = $("<tr/>");
                    var tdCancion = $("<td/>").html("Sin canciones registradas").attr({
                        "colspan": "3",
                        "style": "text-align:center"
                    });
                    trBODY.append(tdCancion);
                    tableBody.append(trBODY);
                }
                tableAlbumbodyTr.append(tableAlbumbodyNume, tableAlbumbodyCanc, tableAlbumbodyMinu);
                tableAlbumbodyHead.append(tableAlbumbodyTr);
                tableAlbumbody.append(tableAlbumbodyHead, tableBody);
                divColTable.append(tableAlbumbody);
                div.append(divColTable);
                $("#bodyModalArtista").append(div);
            });
            console.log(colors);
            if (colors.length > 1) {
                $("#modalInfoArtista .modal-header").attr({
                    style: "background:linear-gradient(to bottom," + colors + ")"
                })
            } else {
                $("#modalInfoArtista .modal-header").attr({
                    style: "background:" + colors
                })
            }

            $("#modalInfoArtista .modal-content").attr({
                style: "border: 1px solid " + colors[0] + ";box-shadow: 5px 5px 8px 0px " + colors[0] + ";"
            })
            $("#modalInfoArtista").modal("show");


            spinner(false);
        }
    });
}
/* metodo para cargar las canciones de todos los artistas junto con la inforamacion artista,album ,genero */
function listadoGeneros() {
    spinner();
    $("#divArtistas").removeClass("oculto");
    $("#tableAllGeneros").removeClass("oculto");
    $("#divAlbums").addClass("oculto");
    $("#divAlbumsxArtista").addClass("oculto");
    $("#divCanciones").addClass("oculto");
    $("#divTableGeneros").removeClass("oculto");
    $("#divTableArtistas").addClass("oculto");
    if (datatableGenero === null) {
        datatableGenero = $("#tableAllGeneros").DataTable({
            ajax: {
                url: $('#tableAllGeneros').data('route'),
                header: { 'X-CSRF-TOKEN': token },
                type: "POST",
                data: function(d) {
                    d._token = token;
                },
                dataSrc: "data"
            },
            drawCallback: function(settings) {
                spinner(false);
                $('#tableAllGeneros tbody').on('click', 'tr', function() {
                    if ($(this).hasClass('selected')) {
                        $(this).removeClass('selected');
                    } else {
                        datatableGenero.$('tr.selected').removeClass('selected');
                        $(this).addClass('selected');
                        var idGenero = $(this).attr("id");
                        console.log("aca pasa ");
                        cargarInfoGenero(idGenero);
                    }
                });
            },
            serverSide: true,
            ordering: false,
            processing: true,
            dom: '<"fondoGris"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3 lengthTop"l>>>tr<"fondoGrisAbajo"<"row"<"col-1"><"col-8 paginateTop paginateCenter"ip><"col-3">>>',
            autoWidth: false,
            columns: [{
                    data: '',
                    width: "15%",
                    class: "text-left",
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
                sZeroRecord: "No se encontraron Generos",
                sEmptyTable: "No se encontraron Generos",
                sInfo: "_TOTAL_ Generos",
                sInfoEmpty: "No se encontraron Generos",
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
        datatableGenero.ajax.reload(null, false);
    }
}
/* la siguiente funcion es para cargar la informacion de albums con respecto al genero seleccionado  */
function cargarInfoGenero(idGenero) {
    $("#rowAlbumsxGenero").html("");
    $("#rowAlbumsxArtista").addClass("oculto");
    $("#rowAlbumsxGenero").removeClass("oculto");
    $("#rowAlbumsxArtista").html("");
    spinner();
    $.ajax({
        url: "/Generos/listarAlbumxGenero",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idGenero: idGenero },
        success: function(data) {
            $("#divAlbumsxArtista").removeClass("oculto");
            var tableArtista = $("<table/>").addClass("table table-bordered tableAlbums");
            var theadArtista = $("<thead/>");
            var trArtista = $("<tr/>");
            var tdArtista = $("<td/>").attr({
                "style": "padding: 21px 50px;"
            });
            var nombreArtista = $("<label/>").html(data.genero).attr({
                "style": "font-size: 20px;font-weight: bold;display:block;"
            });
            var totalAlbum = $("<span/>").attr({
                "style": "font-size: 15px;"
            })
            if (data.totalAlbums > 0) {
                totalAlbum.html(data.totalAlbums + " álbumes, " + data.totalCanciones + " Canciones");
            } else {
                totalAlbum.html("Sin álbumes registrados");
            }
            tdArtista.append(nombreArtista, totalAlbum);
            trArtista.append(tdArtista);
            theadArtista.append(trArtista);
            tableArtista.append(theadArtista);
            $("#rowAlbumsxGenero").append(tableArtista);
            /* tabla por cada uno de los albums */
            $(data.albums).each(function(i, o) {
                var tableAlbum = $("<table/>").addClass("table table-bordered tableAlbums").attr({
                    style: "background-color:" + o.color
                });
                var theadAlbum = $("<thead/>");
                var tbodyAlbum = $("<tbody/>");
                var trAlbum = $("<tr/>");
                var tdAlbum = $("<td/>").attr({
                    "style": "padding: 21px 50px;"
                });
                var divThead = $("<div/>").attr({
                    "style": "display:flex"
                });
                var img = $("<img/>");
                img.attr({
                    id: "imgAlbum_" + o.idAlbum,
                    src: o.imageAlbum,
                    class: "img-fluid",
                    style: " width: 10%;"

                });
                var nombreAlbum = $("<label/>").html(o.nameAlbum).attr({
                    "style": "font-size: 20px;font-weight: bold;display: flex;"
                });
                var artistaAnio = $("<label/>").html(o.artista + " · <span>" + o.anio + "</span>").attr({
                    "style": "font-size: 20px;font-weight: bold;"
                });
                var divCol = $("<div/>").addClass("col-lg-8 col-8").attr("style", "display: block;");
                divCol.append(nombreAlbum, artistaAnio)
                divThead.append(img, divCol);
                tdAlbum.append(divThead);
                trAlbum.append(tdAlbum);
                theadAlbum.append(trAlbum);
                tableAlbum.append(theadAlbum);
                var tableCanciones = $("<table/>").addClass("table tableAlbums table-hover");
                var theadCanciones = $("<thead/>");
                var tbodyCanciones = $("<tbody/>");
                var trCount = $("<tr/>");
                var tdCount = $("<td/>").html(o.canciones.length + " canciones").attr({
                    style: "width:100%;text-align:right;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                    colspan: "3"
                });
                trCount.append(tdCount);
                tbodyCanciones.append(trCount);
                $(o.canciones).each(function(indice, objeto) {
                    var trBody = $("<tr/>");
                    var tdNumeroCancion = $("<td/>").html(objeto.numero_cancion).attr({
                        style: "width:10%;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                        /* style: "width:10%", */

                    });
                    var tdNombreCancion = $("<td/>").html(objeto.name).attr({
                        style: "width:80%;border: 1px solid " + o.color + ";border-left: none;border-right:none",
                        /* style: "width:80%;", */
                    });
                    var tdDuracion = $("<td/>").html(objeto.minutos).attr({
                        style: "width:10%;border: 1px solid " + o.color + ";border-left: none;border-right:none;text-align: right;",
                        /* style: "width:10%", */
                    });
                    trBody.append(tdNumeroCancion, tdNombreCancion, tdDuracion);
                    tbodyCanciones.append(trBody);
                });
                tableCanciones.append(tbodyCanciones);
                var divCanciones = $("<div/>").attr({
                    style: "width: 100%;padding-left: 15px;padding-right: 15px;"
                });
                divCanciones.append(tableCanciones);
                $("#rowAlbumsxGenero").append(tableAlbum, divCanciones);
            });
            spinner(false);
        }
    });
}
/* el siguiente metodo es para añadir un album a la coleccion de un usuario  */
function addAlbumColeccion(idAlbum) {
    spinner();
    $.ajax({
        url: "/Albums/addAlbumColeccion",
        dataType: 'json',
        type: "POST",
        data: { _token: token, idAlbum: idAlbum },
        success: function(data) {
            spinner(false);
            if (data.success) {
                Swal.fire(
                    'Añadido a la colección!',
                    'Se ha añadido correctamente !',
                    'success'
                )
                listadoAlbums();
            } else {
                Swal.fire(
                    'Error!',
                    'ha ocurrido un error añadiendo album a la colección',
                    'error'
                )
            }
        }
    });
}