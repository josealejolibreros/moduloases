// Standard license block omitted.
/*
 * @package    block_ases/tracking_time_control_main
 * @copyright  ASES
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * @module block_ases/tracking_time_control_main
 */
define(['jquery', 'block_ases/bootstrap', 'block_ases/datatables.net', 'block_ases/datatables.net-buttons', 'block_ases/buttons.flash', 'block_ases/jszip', 'block_ases/pdfmake', 'block_ases/buttons.html5', 'block_ases/buttons.print', 'block_ases/sweetalert', 'block_ases/select2', 'block_ases/jqueryui', 'block_ases/moment'], function($, bootstrap, datatablesnet, datatablesnetbuttons, buttonsflash, jszip, pdfmake, buttonshtml5, buttonsprint, sweetalert, select2, jqueryui, moment) {


    return {

        init: function() {


            $(document).ready(function() {
                $('#table_hours').DataTable();
                $(".period_date").datepicker({
                    dateFormat: "yy-mm-dd"
                });

               // get_unreviewed_trackings();

                $("#consult").click(function() {

                    var beginningDate = $("#beginning_date").val();
                    var endingDate = $("#ending_date").val();

                    var result_validation = validateFields(beginningDate, endingDate);
                    if (result_validation != "success") {
                        swal({
                            title: "Advertencia",
                            text: result_validation,
                            type: "warning",
                            html: true
                        });
                    } else {
                        load_hours_report(beginningDate, endingDate);
                    }
                });
            });


            //*Obtain trackings not reviewed by the professional of a chosen monitor.
            function get_unreviewed_trackings() {
                instanceid = get_parameter_url("instanceid");
                monitorid = get_parameter_url("monitorid");

                $.ajax({
                    type: "POST",
                    url: "../managers/tracking_time_control/load_unreviewed_tracking.php",
                    data: {
                        instanceid: instanceid,
                        monitorid: monitorid
                    },

                    success: function(msg) {
                        console.log(msg);
                        if (msg == '') {
                            $("#div_hours").empty();
                            $("#div_hours").append('<h2>Todos los seguimientos del periodo se encuentran revisados.</h2>');
                        } else {
                            $("#div_hours").empty();
                            $("#div_hours").append(msg);
                        }
                    },
                    dataType: "text",
                    cache: "false",
                    error: function(msg) {
                        alert("Error al cargar los seguimientos no revisados.");
                    },
                })




            }


            //*Get the value of a given parameter of the url.
            function get_parameter_url(parameter) {
                var res_parameter;
                var informacionUrl = window.location.search.split("&");
                for (var i = 0; i < informacionUrl.length; i++) {
                    var elemento = informacionUrl[i].split("=");
                    if (elemento[0] == "?" + parameter || elemento[0] == parameter) {
                        res_parameter = elemento[1];
                    }
                }

                return res_parameter;
            }
            //*Perform date format validation.
            function validateFields(beginningDate, endingDate) {

                var regexp = /^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/;

                var validate_begin_date = regexp.exec(beginningDate);
                var validate_end_date = regexp.exec(endingDate);

                if (beginningDate == "" && endingDate == "") {
                    return "Debe llenar todos los campos";
                } else if (beginningDate == "" || endingDate == "") {
                    return "Debe introducir la fecha de inicio y fin del período";
                } else if (validate_begin_date === null) {
                    return "La fecha de inicio no sigue el patrón yyyy-mm-dd. Ejemplo: 2017-10-20";
                } else if (validate_end_date === null) {
                    return "La fecha de fin no sigue el patrón yyyy-mm-dd. Ejemplo: 2017-10-20";
                } else if (beginningDate == endingDate) {
                    return "La fecha de inicio y de fin deben ser diferentes";
                } else {
                    return "success";
                }

            }



            //*Create the hour report table
            function load_hours_report(init, fin) {
                $.ajax({
                    type: "POST",
                    url: "../managers/tracking_time_control/load_hours_report.php",
                    data: {
                        initial_hour: init,
                        final_hour: fin
                    },

                    success: function(msg) {
                        console.log(msg);
                        if (msg == '') {
                            $("#div_hours").empty();
                            $("#div_hours").append('<h2>No existen registros de seguimientos en el dia de hoy</h2>');
                        } else {
                            $("#div_hours").empty();
                            $("#div_hours").append('<table id="tableHours"  class="display" cellspacing="0" width="100%" ><thead><thead></table>');
                            var table = $("#tableHours").DataTable(msg);
                            $('#div_hours #show_details').css('cursor', 'pointer');
                        }
                    },
                    dataType: "json",
                    cache: "false",
                    error: function(msg) {
                        alert("Error al cargar horas trabajadas")
                    },
                })
            }
        }
    };
});