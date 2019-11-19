<?php
function run(){
    //Deloguearse e ir al Landing page
    mw_setEstaLogueado("","","","",false);
    header("Location:index.php?page=home");
}

run();
?>
