function uploadFile( file ){
			xhr = new XMLHttpRequest();
			
			if( file.size > 0 ){
				document.getElementById("mensaje").innerHTML = "<img src='imagenes/cargando.gif'> Cargando...";
			}

			xhr.upload.addEventListener('load',function(e){
				alert('Archivo cargado!');
				document.getElementById("mensaje").innerHTML = "";
				self.close();
			}, false);

			xhr.upload.addEventListener('error',function(e){
				alert('Ha habido un error :/');
			}, false);

			xhr.open('POST','despaletizaje7_foto_guardar.php');

            xhr.setRequestHeader("Cache-Control", "no-cache");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhr.setRequestHeader("X-File-Name", file.name);

            xhr.send(file);
}

var upload_input = document.querySelectorAll('#archivo')[0];

upload_input.onchange = function(){
	uploadFile( this.files[0] );
};