<?php 

    require_once(dirname(__FILE__). '/../../../../config.php');
    require_once('dphpforms_record_updater.php');
    require_once('dphpforms_response_recorder.php');
    require_once(dirname(__FILE__).'/../lib/lib.php');

    function dphpforms_render_recorder($id_form, $rol){
        return dphpforms_generate_html_recorder($id_form, $rol, '-1', '-1');
    };

    function dphpforms_render_updater($id_completed_form, $rol, $record_id){
        return dphpforms_generate_html_updater($id_completed_form, $rol, $record_id);
    };

    if( isset($_GET['form_id']) && isset($_GET['record_id']) ){

        global $USER;
        $rol = get_role_ases($USER->id);
        echo dphpforms_render_updater($_GET['form_id'], $rol, $_GET['record_id']);
    }

    
    /*if( isset($_GET['form_id']) && isset($_GET['rol']) && !(isset($_GET['record_id'])) ){
        echo dphpforms_generate_html_recorder($_GET['form_id'], $_GET['rol'], '-1', '-1');
    }
    die();*/

?>