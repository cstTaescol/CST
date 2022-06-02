<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
switch ($_REQUEST["tipodoc"])
{
	case (31): //nit
	?>
		<table border="0" cellspacing="0" cellpadding="0">
          <tr>
			<td><div class="letreros_tabla">No. Identificacion</div></td>
			<td>
            	<input name="ajax_identificacion" id="ajax_identificacion" type="text" size="30" maxlength="15" tabindex="2" onkeyup="pasar_identificacion(this.value);"  onkeypress="return numeric(event)" /> 
                - <input type="text" maxlength="1" size="1" name="txt_dv_nit" onkeyup="pasar_dv(this.value);"  onkeypress="return numeric(event)" tabindex="3">
                <font color="red">*</font>
            </td>
		  </tr>
		  <tr>
		  
		  <tr>
			<td><div class="letreros_tabla">Razon Social</div></td>
			<td>
            	<input type="text" maxlength="450" name="txt_razon_social" onkeyup="pasar_razonsocial(this.value);" onkeypress="verificar_nombre()" tabindex="4">
                <font color="red">*</font>
            </td>
		  </tr>
		  <tr>
		</table>       
	<?php
	break;

	case (43): //Sin didentificar por la Dian
	?>
		<table width="400" border="0" cellpadding="0" cellspacing="0">
		  <tr>
			<td><div class="letreros_tabla">Razon Social</div></td>
			<td ><input type="text" maxlength="450" size="30" name="txt_razon_social" onkeyup="pasar_razonsocial(this.value);" onkeypress="verificar_nombre()"><font color="red">*</font></td>
		  </tr>
		  <tr>
		</table>       
	<?php
	break;

	default: 
		?>
		<table border="0" cellpadding="0" cellspacing="0">
          <tr>
			<td><div class="letreros_tabla">No. Identificaion</div></td>
			<td><input name="ajax_identificacion" id="ajax_identificacion" type="text" size="30" maxlength="15" onkeyup="pasar_identificacion(this.value);"  onkeypress="return numeric(event)" tabindex="2"/><font color="red">*</font>
            </td>
		  </tr>
		  <tr>
		  
          <tr>
			<td width="180"><div class="letreros_tabla">Primer Apellido</div></td>
			<td><input type="text" maxlength="50" name="txt_primer_apellido" onkeyup="pasar_papellido(this.value);" tabindex="3"><font color="red">*</font></td>
		  </tr>
		  <tr>
			<td><div class="letreros_tabla">Segundo Apellido</div></td>
			<td><input type="text" maxlength="50" name="txt_segundo_apellido" onkeyup="pasar_sapellido(this.value);" tabindex="4"><font color="red">*</font></td>
		  </tr>
		  <tr>
			<td><div class="letreros_tabla">Primer Nombre</div></td>
			<td><input type="text" maxlength="50" name="txt_primer_nombre" onkeyup="pasar_pnombre(this.value);" tabindex="5"><font color="red">*</font></td>
		  </tr>
		  <tr>
			<td><div class="letreros_tabla">Otros Nombres</div></td>
			<td><input type="text" maxlength="50" name="txt_otros_nombres" onkeyup="pasar_snombre(this.value);" tabindex="6"></td>
		  </tr>
		</table>       
        <?php
	break;	
}
?>
