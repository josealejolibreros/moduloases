<?php 
    
    require_once(dirname(__FILE__). '/../../../../config.php');
    header('Content-Type: application/json');

    if( isset( $_GET['record_id'] ) ){
        echo dphpforms_get_record( $_GET['record_id'] );
    }
    
    function dphpforms_get_record($record_id){

        global $DB;

        $sql = "SELECT * FROM {talentospilos_df_preguntas} P 
                INNER JOIN (
                SELECT * FROM (
                    SELECT id AS id_form_preg, id_pregunta AS id_tabla_preguntas FROM {talentospilos_df_form_preg}
                    ) FP INNER JOIN (SELECT * 
                                FROM {talentospilos_df_respuestas} AS R 
                                INNER JOIN 
                                    (
                                        SELECT * 
                                        FROM {talentospilos_df_form_resp} AS FR 
                                        INNER JOIN {talentospilos_df_form_solu} AS FS 
                                        ON FR.id = FS.id_formulario_respuestas 
                                        WHERE FR.id = '".$record_id."'
                                    ) AS FRS 
                                ON FRS.id_respuesta = R.id) RF
                            ON RF.id_pregunta = FP.id_form_preg) TT
                ON id_tabla_preguntas = P.id";

        $list_respuestas = $DB->get_records_sql($sql);
        $list_respuestas = array_values($list_respuestas);
        $respuestas = array();
        if(count($list_respuestas) > 0){
            foreach($list_respuestas as &$respuesta){
                $tmp_respuesta = array(
                    'enunciado' => $respuesta->enunciado,
                    'respuesta' => $respuesta->respuesta,
                    'id_pregunta' => $respuesta->id_tabla_preguntas,
                    'id_relacion_form_pregunta' => $respuesta->id_form_preg,
                    'local_alias' => json_decode($respuesta->atributos_campo)->{'local_alias'},
                );
                array_push($respuestas, $tmp_respuesta);
            }
        }

        return json_encode(
            array(
                'record' => array(
                    'id_solucion' => $list_respuestas[0]->id_formulario_respuestas,
                    'campos' => $respuestas
                )
            )
        );
    }

?>