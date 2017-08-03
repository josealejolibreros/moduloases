--BORRAR ARCHIVO

--- Info de todos los cursos donde hay talentos
SELECT DISTINCT curso.id,
                curso.fullname,
                curso.shortname,

  (SELECT concat_ws(' ',firstname,lastname) AS fullname
   FROM
     (SELECT usuario.firstname,
             usuario.lastname,
             userenrol.timecreated
      FROM mdl_course cursoP
      INNER JOIN mdl_context cont ON cont.instanceid = cursoP.id
      INNER JOIN mdl_role_assignments rol ON cont.id = rol.contextid
      INNER JOIN mdl_user usuario ON rol.userid = usuario.id
      INNER JOIN mdl_enrol enrole ON cursoP.id = enrole.courseid
      INNER JOIN mdl_user_enrolments userenrol ON (enrole.id = userenrol.enrolid
                                                   AND usuario.id = userenrol.userid)
      WHERE cont.contextlevel = 50
        AND rol.roleid = 3
        AND cursoP.id = curso.id
      ORDER BY userenrol.timecreated ASC
      LIMIT 1) AS subc) AS nombre_Profesor
FROM mdl_course curso
INNER JOIN mdl_enrol ROLE ON curso.id = role.courseid
INNER JOIN mdl_user_enrolments enrols ON enrols.enrolid = role.id
WHERE enrols.userid IN
    (SELECT moodle_user.id
     FROM mdl_user moodle_user
     INNER JOIN mdl_user_info_data DATA ON moodle_user.id = data.userid
     INNER JOIN mdl_user_info_field field ON field.id = data.fieldid
     WHERE field.shortname = 'idtalentos'
       AND data.data IN
         (SELECT CAST(id AS VARCHAR)
          FROM mdl_talentospilos_usuario)
       AND moodle_user.id IN
         (SELECT user_m.id
          FROM mdl_user user_m
          INNER JOIN mdl_cohort_members memb ON user_m.id = memb.userid
          INNER JOIN mdl_cohort cohorte ON memb.cohortid = cohorte.id
          WHERE SUBSTRING(cohorte.idnumber
                          FROM 1
                          FOR 2) = 'SP'))
;


--Sacar nombre profesor de un curso.
SELECT concat_ws(' ',firstname,lastname) as fullname
FROM (SELECT usuario.firstname, usuario.lastname, userenrol.timecreated
FROM mdl_course cursoP INNER JOIN mdl_context cont ON cont.instanceid = cursoP.id 
    INNER JOIN mdl_role_assignments rol ON cont.id = rol.contextid INNER JOIN mdl_user usuario ON rol.userid = usuario.id
    INNER JOIN mdl_enrol enrole ON cursoP.id = enrole.courseid INNER JOIN mdl_user_enrolments userenrol ON (enrole.id = userenrol.enrolid AND usuario.id = userenrol.userid) 
WHERE cont.contextlevel = 50 AND rol.roleid = 3 AND cursoP.id = 2
ORDER BY userenrol.timecreated ASC LIMIT 1) as subc;


SELECT *
FROM mdl_course curso INNER JOIN mdl_enrol ON curso.id = mdl_enrol.courseid INNER JOIN mdl_user_enrolments ON (mdl_user_enrolments.enrolid =mdl_enrol.id) 
WHERE curso.id=3;

--- IDS De moodle de todos los talentos
SELECT moodle_user.id
FROM mdl_user moodle_user INNER JOIN mdl_user_info_data data ON moodle_user.id = data.userid
    INNER JOIN mdl_user_info_field field ON field.id = data.fieldid
WHERE field.shortname = 'idtalentos' AND data.data IN (SELECT CAST(id as VARCHAR) FROM mdl_talentospilos_usuario)
;
SELECT moodle_user.id
FROM {user} moodle_user INNER JOIN {user_info_data} data ON moodle_user.id = data.userid
    INNER JOIN {user_info_field} field ON field.id = data.fieldid
WHERE field.shortname = 'idtalentos' AND data.data IN (SELECT CAST(id as VARCHAR) FROM {talentospilos_usuario})
;



--IDs de moodle de los usuarios de una instancia

SELECT pgr.cod_univalle as cod
FROM mdl_talentospilos_instancia inst INNER JOIN mdl_talentospilos_programa pgr ON inst.id_programa = pgr.id
WHERE inst.id_instancia= 19


SELECT user_m.id
FROM mdl_user user_m INNER JOIN mdl_cohort_members memb ON user_m.id = memb.userid INNER JOIN mdl_cohort cohorte ON memb.cohortid = cohorte.id 
WHERE SUBSTRING(cohorte.idnumber FROM 1 FOR 2) = 'SP'





--prueba

SELECT DISTINCT curso.id,
                curso.fullname,
                curso.shortname,

  (SELECT concat_ws(' ',firstname,lastname) AS fullname
   FROM
     (SELECT usuario.firstname,
             usuario.lastname,
             userenrol.timecreated
      FROM mdl_course cursoP
      INNER JOIN mdl_context cont ON cont.instanceid = cursoP.id
      INNER JOIN mdl_role_assignments rol ON cont.id = rol.contextid
      INNER JOIN mdl_user usuario ON rol.userid = usuario.id
      INNER JOIN mdl_enrol enrole ON cursoP.id = enrole.courseid
      INNER JOIN mdl_user_enrolments userenrol ON (enrole.id = userenrol.enrolid
                                                   AND usuario.id = userenrol.userid)
      WHERE cont.contextlevel = 50
        AND rol.roleid = 3
        AND cursoP.id = curso.id
      ORDER BY userenrol.timecreated ASC
      LIMIT 1) AS subc) AS nombre_Profesor
FROM mdl_course curso
INNER JOIN mdl_enrol ROLE ON curso.id = role.courseid
INNER JOIN mdl_user_enrolments enrols ON enrols.enrolid = role.id
WHERE enrols.userid IN
    (SELECT moodle_user.id
     FROM mdl_user moodle_user
     INNER JOIN mdl_user_info_data DATA ON moodle_user.id = data.userid
     INNER JOIN mdl_user_info_field field ON field.id = data.fieldid
     WHERE field.shortname = 'idtalentos' AND moodle_user.id IN (SELECT user_m.id
FROM mdl_user user_m INNER JOIN mdl_cohort_members memb ON user_m.id = memb.userid INNER JOIN mdl_cohort cohorte ON memb.cohortid = cohorte.id 
WHERE SUBSTRING(cohorte.idnumber FROM 1 FOR 2) = 'SP')
       AND data.data IN
         (SELECT CAST(id AS VARCHAR)
          FROM mdl_talentospilos_usuario) )
          
          
          
          
--CATEGORIAS E ITEMS DE UN CURSO


SELECT 
FROM mdl_grade_items as items INNER JOIN mdl_grade_categories 
WHERE




--ESTUDIANTES PILOS EN UN CURSO
SELECT usuario.firstname, usuario.lastname
FROM mdl_user usuario INNER JOIN mdl_user_enrolments enrols ON usuario.id = enrols.userid 
INNER JOIN mdl_enrol enr ON enr.id = enrols.enrolid 
INNER JOIN mdl_course curso ON enr.courseid = curso.id  
WHERE curso.id= 3q AND usuario.id IN (SELECT user_m.id
FROM mdl_user user_m INNER JOIN mdl_cohort_members memb ON user_m.id = memb.userid INNER JOIN mdl_cohort cohorte ON memb.cohortid = cohorte.id 
WHERE SUBSTRING(cohorte.idnumber FROM 1 FOR 2) = 'SP')






