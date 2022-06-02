<div align="center"></div>
<br>
<br>
</body>
</html>

<script language="javascript">
/* ---------- Personalizadas ---------- */
//Validacion de campos numéricos
function numeric(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[0-9]/; // 4 -> Puede ser[0-9-.\s] para permitir agregar puntos al campo de texto
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function ocultarArea(id){
  var area = $("#"+id);
  area.css('display','none');  
}

function mostrarArea(id){
  var area = $("#"+id);
  area.css('display','inline-block');  
}  
</script>