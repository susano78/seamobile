<?php

//function chek_claves_login($user,$pass){
//    $sql="select * from usuarios 
//                    where  usuario='".$user."' 
//                      and  pass='".$pass."'";
//    $rs=get_registros($sql);
//    return count($rs)>0;
//}

//acceso clideclis
function get_usuario_clidecli_login($usuario,$clave,$empresa){
	$sql="select * from clidecli where 
                             id_clidecli=$usuario 
                        and contrasena_clidecli='$clave'
                        and contrasena_clidecli<>'' 
                        and empresa='$empresa' ";
	return get_registros($sql);
}
//fin acceso clideclis

//acceso afiliados
function get_usuario_afiliado_login($usuario,$clave,$empresa){
	$sql="select a.id_afiliado
	            ,a.nombre 
				,a.id_empresa
				,a.cliente_id
				from afiliados a
				inner join empresas e 
				on e.id_e = a.id_empresa 
				where 
				a.dni='$usuario' 
				and a.contrasena='$clave'
				and a.contrasena<>'' 
				and e.empresa='$empresa'";
	return get_registros($sql);
}
//fin acceso afiliados

//acceso afiliados_solo_clientes
function get_usuario_afiliados_solo_clientes_login($usuario,$clave,$empresa){
	$sql="select a.id_afiliado
	            ,a.nombre 
				,a.id_empresa
				,a.cliente_id
				from afiliados_solo_clientes a
				inner join empresas e 
				on e.id_e = a.id_empresa 
				where 
				a.dni='$usuario' 
				and a.contrasena='$clave'
				and a.contrasena<>'' 
				and e.empresa='$empresa'";
	return get_registros($sql);
}
//fin acceso afiliados_solo_clientes


//acceso clientes
function get_usuario_cliente_login($usuario,$clave,$empresa){
	$sql="select * from clientes where tipo='cliente' 
	                               and activo='SI'
	                               and usuario_cliente='$usuario' 
								   and contrasena_cliente='$clave'
								   and usuario_cliente<>'' 
								   and contrasena_cliente<>'' 
								   and empresa='$empresa' ";
	return get_registros($sql);
}
//fin acceso clientes

// acceso usuarios ---------------------------------------------------------------
function get_usuario_login($usuario,$clave,$empresa){
	$cond=" and usuario='$usuario' and pass='$clave' and empresa='$empresa' ";
	return get_usuarios_activos($cond);
}

function get_usuarios_activos($cond){
	$sql="SELECT * FROM usuarios 
	       WHERE acceso='SI'
		   and 1=1 ".$cond;
	return get_registros($sql);
}
// fin acceso usuarios ---------------------------------------------------------------

?>
