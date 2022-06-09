<table width="100%" border="0">
    <tr>
        <td width="33%" rowspan="3" align="center"><img src="<?= base_Father ?>imagenes/logo.png" alt="" align="absmiddle" /></td>
        <td width="34%">&nbsp;</td>
        <td width="33%" colspan="2">
            <div align="center" id="hora" style="position:relative; background-color:#FFF; width:70px; left:150px; border-radius: 1em; border-color: #999; border-width: 2px; border-style: solid;"></div>
        </td>
    </tr>
    <tr>
        <td rowspan="2" align="center">
            <p style="font-weight:bold; font-size:18px">SISTEMA INTEGRAL DE CARGA</p>
        </td>
        <td rowspan="2" align="right"><img src="<?= base_Father ?>imagenes/sesion.png" align="absmiddle"></td>
        <td>Sesi&oacute;n:</td>
    </tr>
    <tr>
        <td><?= $usuario->nombre ?></td>
    </tr>
</table>

<table align="center" border="0">
    <tr>
        <td>
            <hr />
            <div class="menu" align="center">
                <ul class="nav">
                    <!-- Archivo -->
                    <li class="titulos_menu_principal">
                        <a href="#">Archivo <img src="<?= base_Father ?>imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="<?= base_Father ?>contrasena.php">Cambio de Clave</a></li>
                            <li><a href="#">Ajustes<span class="flecha"><img src="<?= base_Father ?>imagenes/menu_next.png" border="0" align="absmiddle" /></span></a>
                                <ul style="z-index:2;">
                                    <li><a href="<?= base_url ?>Central/ajusteManualElemetos">Ajuste Manual Elementos</a></li>
                                </ul>
                            </li>
                            <li><a href="<?= base_Father ?>Central.php">Regresar a CST</a></li>
                            <li><a href="<?= base_Father ?>cerrar_sesion.php">Salir</a></li>
                        </ul>
                    </li>
                    <!-- Vuelos -->
                    <li class="titulos_menu_principal">
                        <a href="#">Ingreso <img src="<?= base_Father ?>imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="<?= base_url ?>Central/vueloNuevo">
                                    <img src="<?= base_Father ?>imagenes/descargar-act.png" width="21" height="22" border="0" align="absmiddle" /> Registro Pallets
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Inventarios -->
                    <li class="titulos_menu_principal">
                        <a href="#">Inventarios <img src="<?= base_Father ?>imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="<?= base_url ?>Central/inventarioGeneral">General</a></li>
                        </ul>
                    </li>
                    <!-- Despachos -->
                    <li class="titulos_menu_principal">
                        <a href="#">Despachos <img src="<?= base_Father ?>imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li>
                                <a href="<?= base_url ?>Central/vueloDespacho"><img src="<?= base_Father ?>imagenes/avion1.png" width="21" height="22" border="0" align="absmiddle" /> Vuelo</a>
                            </li>
                            <li>
                                <a href="<?= base_url ?>Central/vueloTransferencia"><img src="<?= base_Father ?>imagenes/trasbordo.jpg" width="21" height="22" border="0" align="absmiddle" /> Trasferencia </a>
                            </li>
                        </ul>
                    </li>
                    <!-- Consultas -->
                    <li class="titulos_menu_principal">
                        <a href="#">Consultas <img src="<?= base_Father ?>imagenes/menu_down.png" border="0" align="absmiddle" /></a>
                        <ul>
                            <li><a href="<?= base_url ?>Central/buscarPallet">Buscar Pallet</a></li>
                            <li><a href="<?= base_url ?>Central/buscarVuelo">Buscar Vuelo</a></li>
                            <li><a href="<?= base_url ?>Central/selectorReportes">Reportes</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <hr />
        </td>
    </tr>
</table>
