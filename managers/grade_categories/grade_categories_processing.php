<?php
require_once('grade_categories_lib.php');

 if(isset($_POST['course'])&&isset($_POST['parent'])&&isset($_POST['fullname'])&&isset($_POST['agregation'])&&($_POST['tipo']=="CATEGORÍA")&&isset($_POST['peso'])){
        
        $retorno = insertCategory($_POST['course'],$_POST['parent'],$_POST['fullname'],$_POST['agregation'],$_POST['peso']);
        
        echo $retorno;
        
 }
 
if(isset($_POST['course'])&&isset($_POST['parent'])&&isset($_POST['fullname'])&&isset($_POST['agregation'])&&($_POST['tipo']=="PARCIAL")&&isset($_POST['peso'])){
        
        $retorno = insertParcial($_POST['course'],$_POST['parent'],$_POST['fullname'],$_POST['agregation'],$_POST['peso']);
        
        echo $retorno;
        
 }
 
 if(isset($_POST['course'])&&isset($_POST['parent'])&&isset($_POST['fullname'])&&($_POST['tipo']=="ÍTEM")&&isset($_POST['peso'])){
        
        $retorno = insertItem($_POST['course'],$_POST['parent'],$_POST['fullname'],$_POST['peso'],true);
        
        echo $retorno;
        
 }
 
 if(isset($_POST['course'])&&isset($_POST['type'])&&$_POST['type']=="loadCat"){

        $cursos = getCategoriesandItems($_POST['course']);
        echo $cursos;
    }

?>
