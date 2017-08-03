<?php

require_once(dirname(__FILE__). '/../../../../config.php');

/**
 * Función que recupera los mótivos por los cuales un estudiante abandona o aplaza sus estudios en la universidad.
 *
 * @see  get_reasons_dropout()
 * @param void
 * @return Array --> Motivos por los cuales un estudiante puede abandonar o aplazar sus estudios.
 */
 
 function get_reasons_dropout(){
     
     global $DB;
     
     $sql_query = "SELECT * FROM {talentospilos_motivos}";
     $reasons_array = $DB->get_records_sql($sql_query);
     
     return $reasons_array;
 }
 
 /**
 * Función que extrae el conjunto de estados Ases
 *
 * @see get_status_ases()
 * @param void
 * @return Array --> Conjunto de estados Ases
 */
 
 function get_status_ases(){
     
     global $DB;
     
     $sql_query = "SELECT * FROM {talentospilos_estados_ases}";
     $status_ases_array = $DB->get_records_sql($sql_query);
     
     return $status_ases_array;
 }
 
/**
 * Función que extrae el conjunto de estados Icetex
 *
 * @see get_status_icetex()
 * @param void
 * @return Array --> Conjunto de estados Icetex
 */
 function get_status_icetex(){
     
     global $DB;
     
     $sql_query = "SELECT * FROM {talentospilos_estados_icetex}";
     $status_icetex_array = $DB->get_records_sql($sql_query);
     
     return $status_icetex_array;
 }
 
 /**
 * Función que retorna un único seguimiento a partir de su id y el tipo de seguimiento
 *
 * @see load_tracking()
 * @param $id_tracking
 * @param $type_tracking
 * @return Array --> Datos del seguimiento
 */
 function load_tracking($id_tracking, $type_tracking, $id_instance) {
 
     
 }
 
/**
 * Función que extrae los seguimientos de un estudiante dado el id ASES del estudiante
 * el tipo del seguimiento y la instancia asociada al seguimiento y al estudiante ASES
 *
 * @see get_trackings_student()
 * @param id_ases --> id relacionado en la tabla talentospilos_profile
 * @param tracking_type [PARES, GRUPAL]
 * @param id_instance --> Id asociado a la instancia del módulo
 * @return Trackings array
 */
 
 function get_trackings_student($id_ases, $tracking_type, $id_instance){
     
    global $DB;

    $sql_query="SELECT *, seguimiento.id as id_seg 
                FROM {talentospilos_seguimiento} AS seguimiento INNER JOIN {talentospilos_seg_estudiante} AS seg_estudiante  
                                                ON seguimiento.id = seg_estudiante.id_seguimiento  where seguimiento.tipo ='".$tracking_type."';";
    
    if($id_instance != null ){
        $sql_query =  trim($sql_query,";");    
        $sql_query .= " AND seguimiento.id_instancia=".$id_instance." ;";
    }
    
    if($id_ases != null){
        $sql_query = trim($sql_query,";");
        $sql_query .= " AND seg_estudiante.id_estudiante =".$id_ases.";";
    }

    $sql_query = trim($sql_query,";");
    $sql_query .= "order by seguimiento.fecha desc;";
    
    $tracking_array = $DB->get_records_sql($sql_query);
    
    return $tracking_array;
 }
 
/**
 * Función que retorna los seguimientos de un estudiante agrupados por semestre
 * 
 * @see get_tracking_group_by_semester()
 * @param id_ases --> id relacionado en la tabla talentospilos_profile
 * @param tracking_type [PARES, GRUPAL]
 * @param id_instance --> Id asociado a la instancia del módulo
 * @return Trackings array group by semester
 */
 
 function get_tracking_group_by_semester($id_ases = null, $tracking_type, $id_semester = null, $id_instance = null){
     
    global $DB;
    
    $result = get_trackings_student($id_ases, $tracking_type, $id_instance );
    
    if(count($result) != 0){
        $trackings_array = array();
    
        foreach ($result as $r){
            array_push($trackings_array, $r);
        }
        
        $last_semestre = false;
        $first_semester = false;
        
        $sql_query = "SELECT * FROM {talentospilos_semestre}";
        
        if($id_semester != null){
            $sql_query .= " WHERE id = ".$id_semester;
        }else{
            $userid = $DB->get_record_sql("SELECT userid FROM {user_info_data} AS data 
                                                         INNER JOIN {user_info_field} AS field on data.fieldid = field.id 
                                                         WHERE field.shortname='idtalentos' AND data.data='$id_ases';");
            $firstsemester = get_id_first_semester($userid->userid);
            $lastsemestre = get_id_last_semester($userid->userid);
    
            $sql_query .= " WHERE id >=".$firstsemester;
            
        }
        $sql_query.=" order by fecha_inicio DESC";
    
        $array_semesters_seguimientos =  array();
        
        if($lastsemestre && $firstsemester){
            
            $semesters = $DB->get_records_sql($sql_query);
            $counter = 0;
    
            $sql_query ="select * from {talentospilos_semestre} where id = ".$lastsemestre;
            $lastsemestreinfo = $DB->get_record_sql($sql_query);
            
            foreach ($semesters as $semester){
                
                if($lastsemestreinfo && (strtotime($semester->fecha_inicio) <= strtotime($lastsemestreinfo->fecha_inicio))){ //se valida que solo se obtenga la info de los semestres en que se encutra matriculado el estudiante
                
                    $semester_object = new stdClass;
                    
                    $semester_object->id_semester = $semester->id;
                    $semester_object->name_semester = $semester->nombre;
                    $group_tracking_array = array();
                    
                    while(compare_date(strtotime($semester->fecha_inicio), strtotime($semester->fecha_fin),$trackings_array[$counter]->created)){
                        
                        array_push($group_tracking_array, $trackings_array[$counter]);
                        $counter+=1;
                        
                        if ($counter == count($trackings_array)){
                            break;
                        }
                        
                    }
                    
                    foreach($group_tracking_array as $r){
                        $r->fecha = date('d-m-Y', $r->fecha);
                        $r->created = date('d-m-Y', $r->created);
                    }
    
                    $semester_object->result = $group_tracking_array;
                    $semester_object->rows = count($group_tracking_array);
                    array_push($array_semesters_seguimientos, $semester_object);
                }
            }
            
        }
        
        $object_seguimientos =  new stdClass();
        
        $object_seguimientos->semesters_segumientos = $array_semesters_seguimientos;
        
        return $object_seguimientos;
    }else{
        return null;
    }
    
    
    
}

/**
 * Función que retorna el id del primer semestre cursado por el estudiante
 *
 * @param int --- id student 
 * @return int --- id first semester
 */
function get_id_first_semester($id){
    try {
        global $DB;
        
        $sql_query = "SELECT username, timecreated from {user} where id = ".$id;
        $result = $DB->get_record_sql($sql_query);
        
        $year_string = substr($result->username, 0, 2);
        $date_start = strtotime('01-01-20'.$year_string);

        if(!$result) throw new Exception('error al consultar fecha de creación');
        
        $timecreated = $result->timecreated;
        
        if($timecreated <= 0){
            
            $sql_query = "SELECT MIN(courses.timecreated)
                          FROM {user_enrolments} AS userEnrolments INNER JOIN {enrol} AS enrols ON userEnrolments.enrolid = enrols.id 
                                                                   INNER JOIN {course} AS courses ON enrols.courseid = courses.id 
                          WHERE userEnrolments.userid = $id AND courses.timecreated >= ".$date_start;

            $courses = $DB->get_record_sql($sql_query);

            $timecreated = $courses->min;
        }

        $sql_query = "select id, nombre ,fecha_inicio::DATE, fecha_fin::DATE from {talentospilos_semestre} ORDER BY fecha_fin ASC;";
        
        $semesters = $DB->get_records_sql($sql_query);
        
        $id_first_semester = 0; 

        foreach ($semesters as $semester){
            $fecha_inicio = new DateTime($semester->fecha_inicio);

            date_add($fecha_inicio, date_interval_create_from_date_string('-60 days'));
            
            if((strtotime($fecha_inicio->format('Y-m-d')) <= $timecreated) && ($timecreated <= strtotime($semester->fecha_fin))){
                
                return $semester->id;
            }
        }

    }catch(Exeption $e){
        return "Error en la consulta primer semestre";
    }
}

/**
 * Return array of semesters of a student
 *
 * @param string $username_student Is te username of moodlesite 
 * @return array() of stdClass object representing semesters of a student
 */
 function get_semesters_stud($id_first_semester){
     
     global $DB;
     
     $sql_query = "SELECT id, nombre, fecha_inicio::DATE, fecha_fin::DATE FROM {talentospilos_semestre} WHERE id >= $id_first_semester ORDER BY {talentospilos_semestre}.fecha_inicio DESC";
     
     $result_query = $DB->get_records_sql($sql_query);
     
     $semesters_array = array();
     
     foreach ($result_query as $result){
       array_push($semesters_array, $result);
     }
    //print_r($semesters_array);
    return $semesters_array;
}

 function compare_date($fecha_inicio, $fecha_fin, $fecha_comparar){
    
    $fecha_inicio = new DateTime(date('Y-m-d',$fecha_inicio));
    date_add($fecha_inicio, date_interval_create_from_date_string('-30 days'));
    
    // var_dump(strtotime($fecha_inicio->format('Y-m-d')));
    // var_dump($fecha_fin);
    // var_dump($fecha_comparar);
    //print_r(($fecha_comparar >= strtotime($fecha_inicio->format('Y-m-d'))) && ($fecha_comparar <= $fecha_fin));
    return (((int)$fecha_comparar >= strtotime($fecha_inicio->format('Y-m-d'))) && ((int)$fecha_comparar <= (int)$fecha_fin));
}

 function get_id_last_semester($idmoodle){

     $id_first_semester = get_id_first_semester($idmoodle);
     $semesters = get_semesters_stud($id_first_semester);
     if($semesters){
        return  $semesters[0]->id;
     }else{
         return false;
     }
 }
