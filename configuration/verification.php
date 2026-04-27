<?php
   function move_file($sourceFile, $destPath, $destName){
            if (!(is_dir($destPath))){
            mkdir($destPath);
            }
            
            $dhc=date("dmY_His",time());
            $destName=$dhc."_".$destName;
            $dest = "$destPath/$destName";
            
            if(move_uploaded_file($sourceFile ,$dest)){
                return $dest;
            }
        }
        //pour valider ou non l'extension du fichier
    function valid_extension($file_name, $ext_ok){

        $file_ext = strtolower( substr(strrchr($file_name, '.') ,1) );
        if ( in_array($file_ext, $ext_ok) ){
            return true;
        }
        else{
            return false;
        }
    }
?>