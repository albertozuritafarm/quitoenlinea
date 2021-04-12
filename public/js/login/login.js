$("#FLogin input").focus(function(){ OcultarError($(this).attr("id"), true); $("#DIVRestaurarPassword").slideUp(440); });

$("#FLogin").on("submit", function(event){ 
	$("#DIVRestaurarPassword").slideUp(440);
	var TodoOK = true; var Mensaje = "";
	if (Vacio("TextLogin")) { TodoOK = false; Mensaje = "Debe indicar su nombre de usuario."; MostrarError("TextLogin", Mensaje); }
	else if (!LoginValido($("#TextLogin").val())) { TodoOK = false; Mensaje = "Nombre de usuario inválido."; MostrarError("TextLogin", Mensaje); }
	if (Vacio("TextPassword")) { TodoOK = false; Mensaje = "Debe indicar su contraseña."; MostrarError("TextPassword", Mensaje); }
	if (!TodoOK) { }
	else { EnviarFormulario("login/psLogin.php", "DIVOculto", "FLogin", "ChequearCredenciales", true); }
	return false;
});

$("#LabelRestaurarPassword").click(function(){
	MiConfirm("Su contraseña será restablecida. La nueva contraseña le será enviada a su correo electrónico registrado. ¿Desea continuar?", 
	"WARNING", "Continuar", "Cancelar", function(){ CargarPlantilla("login/psLogin.php", "DIVOculto", "RestaurarPassword", 
	$("#TextLogin").val(), null, true); });
});

function MostrarRestaurarPassword() { $("#DIVRestaurarPassword").slideDown(440); }

$(document).ready(function(e) { $("#TextLogin").focus(); });