<?php 
session_start(); /*     "This product includes PHP software, freely available from
     						<http://www.php.net/software/>". */
require("config/configuracion.php");
require("config/control_tiempo.php");
$fecha=date("Y-m-d");
$hora=date("H:i:s");

if(isset($_REQUEST['id_guia']))
{
    $id_guia=$_REQUEST['id_guia'];
    //Datos de la guia hija
    $sql="SELECT hija,master,id_tipo_actuacion_aduanera,id_consignatario,courier_id_entidad FROM guia WHERE id = '$id_guia'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 1'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $hija=$fila["hija"];    
    $id_master=$fila["master"];
    $id_tipo_actuacion_aduanera=$fila["id_tipo_actuacion_aduanera"];
    $id_entidad=$fila["courier_id_entidad"];

    //Datos de la entidad
    $sql="SELECT nombre FROM courier_entidades WHERE id = '$id_entidad'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 2'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $entidad=$fila["nombre"];

    //Datos de la Guia master 
    $sql="SELECT id_consignatario FROM guia WHERE id = '$id_master'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));
    $fila=mysql_fetch_array($consulta);
    $id_consignatario=$fila["id_consignatario"];

    //Metadata selectiva por tipo de actuacion Aduanera
    $metadataVehiculo='';
    $metadatoFuncionarioAutorizador='';
    $metadatoFuncionarioCourier='';
    $metadatoPlanillaEnvio='';

    if ($id_tipo_actuacion_aduanera == 3)
    {
        $metadatoPlanillaEnvio='
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Planilla de Env&iacute;o No</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <input type="text" name="planillaEnvio" id="planillaEnvio" size="10" maxlength="15" value="" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Dep&oacute;sito</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <input type="text" name="deposito" id="deposito" size="30" maxlength="60" value="" />
                                    </td>
                                </tr>
                                ';
    }
    else
    {
         $metadatoPlanillaEnvio=' 
                                <input type="hidden" name="planillaEnvio" id="planillaEnvio" value="" />
                                <input type="hidden" name="deposito" id="deposito" value="" />
                                ';       
    }
    switch($id_tipo_actuacion_aduanera)
    {
        case 2:
            $metadataVehiculo='
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Veh&iacute;culo</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <input type="text" name="vehiculo" id="vehiculo" size="6" maxlength="6" value="" />
                                    </td>
                                </tr>
                            ';        
        break;

        default:
            $metadataVehiculo .= '
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Veh&iacute;culo</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <select name="vehiculo" id="vehiculo">
                                ';

            //Datos del Vehiculo asociado al courier
            $sql="SELECT id, placa FROM vehiculo_courier WHERE id_consignatario = '$id_consignatario' AND estado='A'";
            $consulta=mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));
            while($fila=mysql_fetch_array($consulta))
            {
                $metadataVehiculo .= '<option value="'.$fila["placa"].'">'.$fila["placa"].'</option>'; 
                                                
            }

            $metadataVehiculo .= '</select>
                                    </td>
                                  </tr>
                                  ';                                  
        break;
    }

    //Datos del los funcionarios que Autoriza el procedimiento    
    $metadatoFuncionarioAutorizador .= '
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionario '.$entidad.' Autoriza</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <select name="autorizador" id="autorizador">
                                ';       
    
    $sql="SELECT id, nombre FROM courier_funcionario WHERE id_entidad= '$id_entidad' AND estado='A' ORDER BY nombre ASC";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));
    while($fila=mysql_fetch_array($consulta))
    {
        $metadatoFuncionarioAutorizador .= '<option value="'.$fila["id"].'">'.$fila["nombre"].'</option>'; 
    }
    $metadatoFuncionarioAutorizador .= '</select>
                                    </td>
                                  </tr>
                                  ';


    //Datos del los funcionarios Courier
    $metadatoFuncionarioCourier .= '
                                <tr>
                                    <td class="celda_tabla_principal"><div class="letreros_tabla">Funcionario del Courier</div></td>
                                    <td class="celda_tabla_principal celda_boton">      
                                        <select name="courier" id="courier">
                                ';                                  
    $sql="SELECT id, nombre FROM courier_funcionario WHERE id_entidad= '7' AND id_consignatario = '$id_consignatario' AND estado='A'";
    $consulta=mysql_query($sql,$conexion) or die (exit('Error 3'.mysql_error()));
    while($fila=mysql_fetch_array($consulta))
    {
        $metadatoFuncionarioCourier .= '<option value="'.$fila["id"].'">'.$fila["nombre"].'</option>'; 
    }
    $metadatoFuncionarioCourier .= '</select>
                                    </td>
                                  </tr>
                                  ';
}
else
{
    echo '
        <script>
            alert("Error: El servidor no pudo obtener la informacion, intentelo de nuevo y comuniquese con el soporte tecnico.");
            document.location="base.php";
        </script>
        ';
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="tema/estilo.css" rel="stylesheet" type="text/css" />
    <script src="js/srcNewCalendar/js/jscal2.js"></script>
    <script src="js/srcNewCalendar/js/lang/es.js"></script>
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/jscal2.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/border-radius.css" />
    <link rel="stylesheet" type="text/css" href="js/srcNewCalendar/css/steel/steel.css" />    
    <link rel="stylesheet" href="js/bootstrap.min.css" >
    <script src="js/jquery-3.3.1.min.js" ></script>
    <script src="js/popper.min.js" ></script>   
    <title>Entregar Gu&iacute;a</title>
</head>
<body>
<p class="titulo_tab_principal">Entrega de Gu&iacute;a</p>
<p class="asterisco" align="center">Gu&iacute;a No. <?php echo $hija ?> </p>

<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" onsubmit="return validar();" >
	<table align="center" width="300">
        <!-- No. Acta  -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">No. de Acta</div></td>
            <td class="celda_tabla_principal celda_boton">
                <input type="text" name="acta" id="acta" size="15" maxlength="15" value=""/>
                <script language="JavaScript" type="text/javascript">
                    document.getElementById('acta').focus();
                </script>
            </td>
        </tr>
        <!-- Fecha Acta -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Fecha</div></td>
            <td class="celda_tabla_principal celda_boton">                
                <input type="text" size="10" name="f_date1" id="f_date1" readonly="readonly" value="<?php echo $fecha ?>" /><button id="f_btn1" type="button">...</button>
            </td>
        </tr>
        <!-- Justificacion -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Justificaci&oacute;n</div></td>
            <td class="celda_tabla_principal celda_boton">      
                <textarea id="justificacion" name="justificacion" cols="40" rows="4"></textarea>                          
            </td>
        </tr>
        <!-- Vehiculo -->
        <?php
            echo $metadataVehiculo;
        ?>
        <!-- Conductor -->
        <tr>
            <td class="celda_tabla_principal"><div class="letreros_tabla">Conductor</div></td>
            <td class="celda_tabla_principal celda_boton">      
                CC&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="10" maxlength="13" name="ccConductor" id="ccConductor" onKeyPress="return numeric(event)"/><br>
                Nombre<input type="text" size="20" name="nombreConductor" id="nombreConductor"/>
            </td>
        </tr>
        <!-- Funcionarios y Planillas -->
        <?php
            echo $metadatoFuncionarioAutorizador;
            echo $metadatoFuncionarioCourier;
            echo $metadatoPlanillaEnvio;            
        ?>
    </table>
    <input type="hidden" name="id_guia" id="id_guia" value="<?php echo $_REQUEST["id_guia"]; ?>">  
    <input type="hidden" name="id_tipo_actuacion_aduanera" id="id_tipo_actuacion_aduanera" value="<?php echo $id_tipo_actuacion_aduanera; ?>">        
    <!-- Menu interno -->   
    <table width="450px" align="center">
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal"><div class="letreros_tabla">MENU DE ACCIONES</div></td>
        </tr>
        <tr>
          <td align="center" valign="middle" class="celda_tabla_principal celda_boton">
            <button type="reset" name="reset" id="reset" >
                <img src="imagenes/descargar-act.png" title="Limpiar" />
            </button>
            <button type="button" name="guardar" id="guardar" onclick="validar()">
                <img src="imagenes/guardar-act.png" title="Guardar" />
            </button>
          </td>
        </tr>
</form>
</body>
</html>
<script language="javascript">
    //<![CDATA[
      Calendar.setup({
        inputField : "f_date1",
        trigger    : "f_btn1",
        onSelect   : function() { this.hide() },
        //showTime   : 12,
        //Con Hora--> dateFormat : "%Y-%m-%d %I:%M %p"
        dateFormat : "%Y-%m-%d"
      });
    //]]>   

    //Validacion de campos numéricos
    function numeric(e) 
    { 
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 

    //Validacion de campos numéricos
    function numeric2(e) 
    { 
        tecla = (document.all) ? e.keyCode : e.which; // 2
        if (tecla==8) return true; // 3
        patron =/[0-9-.]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
        te = String.fromCharCode(tecla); // 5
        return patron.test(te); // 6
    } 

    // funcion para validar
    function validar()
    {   
        if (document.forms[0].acta.value=="")
        {
            alert("Atencion: Debe ingresar un NUMERO DE ACTA.");
            document.forms[0].acta.focus();
            return(false);
        }

        if (document.forms[0].justificacion.value=="")
        {
            alert("Atencion: Ingrese una JUSTIFICACION de la Actuacion Aduanera.");
            document.forms[0].justificacion.focus();      
            return(false);
        }
        
        if (document.forms[0].vehiculo.value=="")
        {
            alert("Atencion: Debe seleccionar un VEHICULO.");
            document.forms[0].vehiculo.focus();      
            return(false);      
        }  

        if (document.forms[0].ccConductor.value=="")
        {
            alert("Atencion: Debe ingresar El numero de CEDULA del conductor.");
            document.forms[0].ccConductor.focus();      
            return(false);      
        }  

        if (document.forms[0].nombreConductor.value=="")
        {
            alert("Atencion: Debe ingresar el NOMBRE del conductor.");
            document.forms[0].nombreConductor.focus();      
            return(false);      
        }  

        if (document.forms[0].autorizador.value=="")
        {
            alert("Atencion: Debe seleccionar un FUNCIONARIO QUE AUTORIZA.");
            document.forms[0].autorizador.focus();      
            return(false);      
        }  

        if (document.forms[0].courier.value=="")
        {
            alert("Atencion: Debe seleccionar un FUNCIONARIO DEL COURIER.");
            document.forms[0].courier.focus();      
            return(false);      
        }  

        if (document.forms[0].id_tipo_actuacion_aduanera.value=="3")
        {

            if (document.forms[0].planillaEnvio.value=="")
            {
                alert("Atencion: Debe ingresar el numero de PLANILLA DE ENVIO");
                document.forms[0].planillaEnvio.focus();      
                return(false);      
            }  

            if (document.forms[0].deposito.value=="")
            {
                alert("Atencion: Debe ingresar el nombre del DEPOSITO");
                document.forms[0].deposito.focus();      
                return(false);      
            }      
        }  
        guardar();
    }

    //Guarda el formulario
    function guardar()
    {
        var datosFormulario={
                                acta:$("#acta").val(),
                                fechaActa:$("#f_date1").val(),
                                justificacion:$("#justificacion").val(),
                                vehiculo:$("#vehiculo").val(),
                                ccConductor:$("#ccConductor").val(),
                                nombreConductor:$("#nombreConductor").val(),
                                planillaEnvio:$("#planillaEnvio").val(),
                                deposito:$("#deposito").val(),
                                courier:$("#courier").val(),
                                autorizador:$("#autorizador").val(),
                                id_tipo_actuacion_aduanera:$("#id_tipo_actuacion_aduanera").val(),
                                id_guia:$("#id_guia").val()
                            }
        $.get("courier_entrega_opciones_guia_hija_finalizacion.php",datosFormulario,resultadoProcesoAgregar);
    }
    function resultadoProcesoAgregar(datos_devueltos)
    {
        var respuesta = datos_devueltos.substring(0, 5);
        var coderror = datos_devueltos.substring(6, 7); 
        switch(respuesta)
        {
            case "Error":
                if(coderror == "0")
                {
                    document.location="cerrar_sesion.php";
                }
                else
                {
                    alert(datos_devueltos);
                }           
            break;

            default:
                alert("Registro Creado con Exito");
                recarga_ventana_padre(datos_devueltos);            
            break;
        }
    }

    function recarga_ventana_padre(valor)
    {   
        window.opener.location="courier_despacho_opciones_hija.php?id_registro="+valor;
        self.close();
    }

</script>