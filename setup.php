<?php

  require("./models/security/security.model.php");

  // geenrar la contraseña salada (salting)
  $usuario = obtenerUsuarioPorEmail('admin@demo.com');
  if(count($usuario) == 0){
    $pswd = 'CorpDemo%33';
    $fchingreso = time();
    $pswdSalted = "";
    if($fchingreso % 2 == 0){
      $pswdSalted = $pswd . $fchingreso;
    }else{
      $pswdSalted = $fchingreso . $pswd;
    }

    $pswdSalted = md5($pswdSalted);

    $result = insertUsuario('Administrador',
                  'admin@demo.com',
                  $fchingreso, $pswdSalted, 'ADM');

    echo "Administrador creado: correo: admin@demo.com, contraseña: CorpDemo%33";
    echo "<br /><br />Cambiarla lo mas pronto posible";
  }
?>
