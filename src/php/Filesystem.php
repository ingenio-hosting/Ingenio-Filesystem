<?php
namespace Php\Filesystem\Filesystem;

class Filesystem{
    public $RUTA_AB;
    public $RUTA_WEB;
    public $ruta;   
    public $GestorRutas;
    public $FormGestorRutas;
    public $direct = "";
    public $modo = [
        ['r','r - Solo lectura. Comienza al principio del fichero.'],
        ['r+','r+ - Lectura/Escritura. Comienza al principio del fichero.'],
        ['w','w -  Escritura. Abre y trunca el fichero. Coloque el puntero de fichero al principio.'],
        ['w+','w+ - Lectura/Escritura. Abre y trunca el fichero;Coloque el puntero al principio.'],
        ['a','a - Solo escritura. Añade al final del fichero y lo crea si no existe.'],
        ['a+','a+ - Lectura/Escritura. Preserva y escribe al final del fichero'],
        ['x','x -  Solo escritura. Crea un nuevo fichero. Devuelve FALSO y error si el fichero ya existe'],
        ['x+','x+ - Lectura/Escritura. Crea un nuevo fichero. Devuelve FALSO y error si el fichero ya existe'],
        ['c','c -  Solo escritura. Abre el fichero; o crea un nuevo fichero si no existe. Coloque el puntero de fichero al principio del fichero'],
        ['c+','c+ - Lectura/Escritura. Abre el fichero; o crea un nuevo fichero si no existe. Coloque el puntero de fichero al principio del fichero']
    ];
    private $FormSubirFichero ="";
    private $FormSeleccionarFichero = "";
    private $method = 'get';
    private $action = 'index.php';

    public function __construct(){
        $this->ruta = RUTA_AB;
        $this->RUTA_AB = RUTA_AB;
        $this->RUTA_WEB = RUTA_WEB;        
        $this->FormGestorRutas();
        $this->GestorRutas();
        $this->direct = "";       
        $this->ObtenerEstructuraDirectorios(RUTA_AB,RUTA_WEB);
        $this->setFormSubirFichero();
        $this->setFormSeleccionarFichero();
    }
    public function setMetodAction($method,$action){
        $this->$method = $method;
        $this->action = $action;
    }
    private function setFormSeleccionarFichero(){
        $this->FormSeleccionarFichero .= '            
            <form class="form-control container" method="'.$this->method.'" action="'.$this->action.'">
                <div class="row">
                    <div class="col-lg-12">
                        <h3>SELECCIONAR UNA FICHERO</h3>
                    </div>
                    <div class="col-lg-4">
                        <label>Nombre del fichero:</label>
                        <input type="text" id="nomfile" class="form-control" name="nomfile" />
                    </div>
                    <div class="col-lg-4">
                        <label>Elegir Modo</label>
                    
                        <select class="form-select" name="modo" >
                            <option></option>';        
                               
                                for($i=0; $i < count($this->modo); $i++){
                                    $this->FormSeleccionarFichero .= '<option value="'.$this->modo[$i][0].'" >'.$this->modo[$i][1].'</option>';
                                }
                        $this->FormSeleccionarFichero .= '   
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label>Tipo de Acción:</label>
                        <select class="form-select" name="tipo" onchange="MostraTexto(this.value)">
                            <option></option>
                            <option value="1">LEER</option>
                            <option value="2">ESCRIBIR</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="contenido" style="display:none;">            
                            <label>Contenido del fichero</label>
                            <textarea class="form-control" id="contenido" name="contenido" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <input type="hidden" id="action" name="action" value="ABRIR_UN_FICHERO" />
                        <input type="hidden" id="rutafile" name="ruta" value="'.$this->ruta.'" />
                        <input id="btn-files" class="btn btn-success" type="submit" value="ABRIR FICHERO" />
                        <input class="btn btn-danger" type="reset" value="RESET" />
                    </div>
                </div>
            </form>        
        ';
    }
    public function getFormSeleccionarFichero(){
        return $this->FormSeleccionarFichero;
    }
    private function setFormSubirFichero(){
        $this->FormSubirFichero = '            
            <form class="form-control" method="post" action="'.$this->action.'" enctype="multipart/form-data" >
                <h3>SUBIR UN FICHERO</h3>
                <input class="form-control" type="text" name="nombre" placeholder="Nombre del propietario" />
                <br>
                <label>Subir un fichero:</label>
                <input id="fichero" class="form-control" type="file" name="fichero" placeholder="Subir un fichero..."  />
                <input type="hidden" name="action" value="SUBIR_FICHERO" />
                <input class="btn btn-primary" type="submit" value="SUBIR" />
            </form>
        ';
    }
    public function getFormSubirFichero(){
        return $this->FormSubirFichero;
    }
    public function setDirect($ruta){
        $this->direct = "";
        $this->ObtenerEstructuraDirectorios($ruta,RUTA_WEB);
    }
    public function AbrirFicheros(array $datos){
        $fichero = fopen($datos['ruta'].$datos['nomfile'],$datos['modo']);
        //$datos = fgets($fichero);
        $item = fread($fichero,filesize($datos['ruta'].$datos['nomfile']));
        return $item;   
        fclose($fichero);
                
    }
    public function EscribirFichero(array $datos){
        $fichero = fopen($datos['ruta'].$datos['nomfile'],$datos['modo']);
        if(isset($datos['contenido']) && !empty($datos['contenido'])){
            return $item = fwrite($fichero, $datos['contenido']);                   
        }
        fclose($fichero);
    }
    public function ObtenerEstructuraDirectorios(string $ruta,string $ruta_web = RUTA_WEB){
        // Lógica dibuja la raiz de la ruta.
        // Abrir el directorio de la ruta establecida en el form y obtener carpetas y ficheros.
        // .. / .
        
        $ficheros = opendir($ruta);
        
        $this->direct .= '<ul id="listado" ondragover="dragOverHandler(event);">';
        $hay_ficheros = false;
       
        // Bucle que recorra la ruta y obtenga ficheros y subcarpetas.
        while(($fichero = readdir($ficheros)) !== false){
    
            $ruta = rtrim($ruta, '/');
            $rutaw = rtrim($ruta_web,'/');
            $ruta_completa = $ruta."/".$fichero;        
            
            if($fichero != '.' && $fichero != '..'){
                //$ruta_web = $rutaw."/".$fichero;
                $hay_ficheros = true;
                if(is_dir($ruta_completa)){
                    $ruta_web = RUTA_WEB."/".$fichero;
                    $wfichero =  $fichero;
                    // es un subdirectorio
                    $this->direct .= '<li ondrop="dropHandler(event);">
                                    <a href="#" id="btn-'.$fichero.'" class="btn-fichero" onclick="mostrarInput(\''.$ruta_completa.'\',\''.$fichero.'\')">
                                    <i class="fa-solid fa-pen-to-square"></i></a>';
                                    if($hay_ficheros){
                                        $this->direct .= '<a href="#" class="btn-fichero" onclick="RemoveDir(\''.$ruta_completa.'\')"><i class="fa-solid fa-trash"></i></a>';    
                                    }
                                    else
                                    {
                                        $this->direct .= '<a href="#" class="btn-fichero" onclick=""><i class="fa-solid fa-trash"></i></a>';      
                                    }
                                    $this->direct .= '
                                    <a href="#" ondblclick="mostrarInput(\''.$ruta_completa.'\',\''.$fichero.'\')">
                                            <i class="fa-regular fa-folder"></i><span id="span-'.$fichero.'"> '.$fichero.'</span></a>'.
                                            '<input id="btn-rename-'.$fichero.'" class="rename" type="text" value="'.$fichero.'" onchange="Rename(this.value,\''.$ruta_completa.'\',\''.$ruta.'/'.'\')" onmouseleave="ocultarInput(\''.$fichero.'\')"/>'; 
                                            //$direct .= '<ul>';
                                            $this->direct .= $this->ObtenerEstructuraDirectorios($ruta_completa,$ruta_web);
                                            //$direct .= '</ul>';
                     $this->direct .='</li>';
                }
                else
                {
                    // ficheros
                    $ruta_web = RUTA_WEB.'';
                    $this->direct .= '<li ondrop="dropHandler(event);">                
                    <a href="#" id="btn-'.str_ireplace('.','-',$fichero).'" class="btn-fichero" onclick="mostrarInput(\''.$ruta_completa.'\',\''.str_ireplace('.','-',$fichero).'\')">
                    <i class="fa-solid fa-pen-to-square"></i></a>
                    <a href="#" class="btn-fichero" onclick="RemoveFile(\''.$ruta_completa.'\')"><i class="fa-solid fa-trash"></i></a>
                    <a href="#" ondblclick="mostrarInput(\''.$ruta_completa.'\',\''.str_ireplace('.','-',$fichero).'\')" onclick="SeleccionarFichero(\''.$rutaw.'/'.'\',\''.$ruta.'/'.'\',\''.$fichero.'\')">
                    
                    <i class="fa-solid fa-file"></i> 
                     <span id="span-'.str_ireplace('.','-',$fichero).'">'.$fichero.'</span></a>'.
                     '<input id="btn-rename-'.str_ireplace('.','-',$fichero).'" class="rename" type="text" value="'.$fichero.'" onchange="Rename(this.value,\''.$ruta_completa.'\',\''.$ruta.'/'.'\')" onmouseleave="ocultarInput(\''.str_ireplace('.','-',$fichero).'\')"/> ';
                     $this->direct .= '</li>';
                }           
               
            }
        }
        if (!$hay_ficheros) {
            $this->direct .= '<li><em>Este directorio está vacío</em></li>';
        }
        $this->direct .= '</ul>';
        closedir($ficheros);
       
     
        
    }
    public function FiltrarNombreFichero($nombre_fichero){
        $nombre_fichero = trim($nombre_fichero);
        $nombre_fichero = str_ireplace(" ","-",$nombre_fichero);
        $num = strlen($nombre_fichero);
        $ext = substr($nombre_fichero,strripos($nombre_fichero,'.',-1),10);   
        if($num > 1 && $num < 250){
            return $nombre_fichero;
        }
        else
        {
            return "fichero-defecto".$ext;
        }    
    }
    private function GestorRutas(){       
        $this->GestorRutas .= $this->FormGestorRutas;
        $this->GestorRutas .='<span id="alert-ruta">'.$this->ruta.'</span>';
        $this->GestorRutas .='<button id="btn-1" class="btn btn-primary" onclick="MostrasRem()"><i class="fa-solid fa-pen-to-square"></i></button>';
    }
    private function FormGestorRutas(){
        $this->FormGestorRutas .='
        <form id="form-direc" class="row g-3" method="get" action="index.php" style="display:none">
            <div class="col-auto">
                <input id="rutao" class="form-control" type="text" placeholder="Escribe una ruta.." name="ruta" 
            value="'.RUTA_AB.'" />
            </div>
            <div class="col-auto">    
                <input type="hidden" name="action" value="SELECCIONAR_RUTA" />
                <input type="submit" class="btn btn-success" value="SELECCIONAR" />
            </div>
            <div class="col-auto"> 
                <input type="reset" class="btn btn-danger" value="RESET" />
            </div>
        </form>';
        

    }
    public function SubirFichero($datos){
        $nombre_fichero = $_FILES['fichero']['name'];
        $ruta_completa = $_FILES['fichero']['full_path'];
        $tipo = $_FILES['fichero']['type'];
        $tmp = $_FILES['fichero']['tmp_name'];
        $error = $_FILES['fichero']['error'];
        $size = $_FILES['fichero']['size'];
        $nombre_fichero = $this->FiltrarNombreFichero($nombre_fichero);        
        if(move_uploaded_file($tmp , RUTA_AB.$nombre_fichero)){
            return $msn = "achivo subido correctamente";
        }
        else
        {
            return $msn = "El archivo no se ha podido subir";
        }           
    }
    private function LeerFichero(){
        $filename = "file.txt";
        $sep_row = "\n"; // separator for each row in txt file
        $sep_col = "|"; // separator for each col in txt file

        $fp = fopen($filename, "r");
        $rows = array();

        if(filesize($filename) > 0){
        $content = fread($fp, filesize($filename));
        $rows = explode($sep_row, $content);
        fclose($fp);
        }

        foreach($rows as $row){
        $cols = explode($sep_col, $row);
        foreach($cols as $col) echo $col . ", ";
        echo "<hr/>";
        }
    }
    public function LeerCSV(string $fichero, string $modo){
        $file = fopen($fichero,$modo);
        $csv = fgetcsv($file);
        fclose($file);
        return $csv;        
    }
    public function CrearCSV(string $fichero, string $modo, array $myarray){
       
          
          $file = fopen($fichero,$modo);
          
          foreach ($myarray as $line) {
            fputcsv($file, $line);
          }
          
          fclose($file);
    }
    public function RenombrarFicherosAjax(array $datos){
        if(rename($datos['ruta_completa'],$datos['ruta'].$datos['nuevo'])){
            $res['msn'] = 'Ficho o carpeta renombrado correctamente';
            $res['valor'] = true;
            return json_encode($res);  
        }
        else
        {
            $res['msn'] = 'Fichero o carpeta no ha sido renombrado correctamente';
            $res['valor'] = false;
            return json_encode($res); 
        }
    }
    public function BorrarFileAjax(array $datos){
        if(unlink($datos['ruta_completa'])){
            $res['msn'] = 'Ficho borrado correctamente';
            $res['valor'] = true;
            return json_encode($res);  
        }
        else
        {
            $res['msn'] = 'Fichero no ha sido borrado correctamente';
            $res['valor'] = false;
            return json_encode($res); 
        }
    }
    public function BorrarDirectoriosAjax(array $datos){
        if(rmdir($datos['ruta_completa'])){
            $res['msn'] = 'Directorio borrado correctamente';
            $res['valor'] = true;
            return json_encode($res);  
        }
        else
        {
            $res['msn'] = 'Directorio no se ha podido borrar o contiene ficheros';
            $res['valor'] = false;
            return json_encode($res); 
        }
    }
   
}