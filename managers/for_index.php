<<<<<<< HEAD
<?php
require('query.php');
if(isset($_POST['cohorte']) && isset($_POST['idinstancia'])){
    $result = new stdClass();
    $result->cohorts = getConcurrentCohortsSPP($_POST['idinstancia']);
    $result->enfasis = getConcurrentEnfasisSPP();
    echo json_encode($result);
}else{
    echo 'no';
}
=======
<?php
require('query.php');
if(isset($_POST['cohorte']) && isset($_POST['idinstancia'])){
    $result = new stdClass();
    $result->cohorts = getConcurrentCohortsSPP($_POST['idinstancia']);
    $result->enfasis = getConcurrentEnfasisSPP();
    echo json_encode($result);
}else{
    echo 'no';
}
>>>>>>> 97c7d23d80c7365c0b40027b0d4abac40b2e33b4
?>