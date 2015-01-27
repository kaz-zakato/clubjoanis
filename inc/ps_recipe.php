<?php
/************************************************************************
* The ps_recipe class
*
* methods :
*       show_snapshot()
*************************************************************************/

class ps_recipe {
	var $classname = "ps_recipe";

	/**************************************************************************
	** name: uploadImg()
	** description:
	** parameters:
	** returns:
	***************************************************************************/
	
	function uploadImgRec($image_name, $id, $table, $key, $dir=""){
		global $db;

		if(isset($_FILES[$image_name])){
			$maxsize 			= 50000;
			$filesize 		= $_FILES[$image_name]['size'];
			$filetmpname	= $_FILES[$image_name]['tmp_name'];
			$filename			= $_FILES[$image_name]['name'];
			$filetype			= $_FILES[$image_name]['type'];
			$new_image 		= false;
			$dir          = "../images/recipes/";
			
			if ($filesize>0 && $filesize < $maxsize) {
				//chmod($_FILES[$image_name]['tmp_name'], 0766);
				//copy($_FILES[$image_name]['tmp_name'], $dir.$filename);
				if (!move_uploaded_file($_FILES[$image_name]['tmp_name'], $dir . $filename)){
					print "IMAGE : Erreur d'écriture sur le disque. ";
				}else{
				  $SQL = "UPDATE $table SET $image_name='$filename' WHERE $key=$id";
 					if(!$db->query($SQL)) print "IMAGE : Erreur d'écriture dans la base de données. ";
					else $new_image = $filename;

					create_thumbnail($dir . $filename, $dir . $filename, 100, 252, 2);
				}
			} else {
				if ($filesize > $maxsize)
					echo "IMAGE : Impossible d'enregistrer ce fichier. La taille maximum autorisée est de $maxsize bytes. (Votre fichier: $filesize bytes). ";
				print "IMAGE : Erreur de lecture du fichier. ";
			}
		}
		return $new_image;
	}
}
?>
