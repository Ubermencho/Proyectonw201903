# Esquema de Seguridad

## Primero la verificación y validación de credenciales ( LOGIN )
Esto no se debe hacer
```
select * from users where username=? and userpswd=?;
```

Si hay mas de x intentos fallidos seguidos
intentosFallidos = 0 ;
a) guardar en bitacora de seguridad
b) bloqueo de cuenta
c) enviar correo al correo registrado y bloquear por tiempo prudente

Que es lo que se debe hacer?
```
select * from users where username=?;
```
Verificar estado del usuario si está activo

Verificar la contraseña

    hash  vrs hash en db

Verificar la vigencia de la contraseña
si ok reseteamos contadores negativos
    intentosFallidos
    ultimoacceso
    bitacora de accesos

    si la fecha de hoy no es menor o igual a la fecha de expiracion
      solicitamos el cambio de contraseña
          bitacora de cambio de contraseña
          se cambia la vigencia de la nueva contraseña


si no es ok
    intentosFallidos
    estadoUsuario
    ultimointentofallido
    bitcoradefallidos
    disparar protocolos como envio de correo, notificacion por sms


## 2 Autenticación
    Verificar que tiene una sesión con credenciales validas

    Como puede fallar
        el tiempo de la validacion ha expirado
        cierre manual de sesión

  ## 3 Verificación de Acceso

      Se verifica que se puede o no realizar


Esquema de Datos

 usuario -- perfil unico  !!!!!!NO USAR

 fulanito -- publico
 menganito -- pulbico, administrador
 paquita -- publico , auditor
 gerente de operaciones -- publico, vendedor, auditor, almacenes

 (usuarios, roles,  usuarios_x_roles)

 estaEnRol($usuario, ['administrador','publico']) | verdadero o falso;

 p_rutas
 p_ruta
 f_rutas_ins
 f_rutas_del
 f_rutas_upd
 f_rutas_del

(funciones, funciones_rol)

autorizado($usuario,'p_ruta');


 5 tablas
