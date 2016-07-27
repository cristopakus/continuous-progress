<?php
class upload{
	
   public $error = array();
   public $authorized_image_extensions = array("jpg", "jpeg", "gif", "png");
   public $authorized_file_extensions = array("pdf", "csv","docx","ppt","doc","xlsx","xls");
   public $path_file = array('images/','documents/');
   public $destination;
   public $uploaded_file;
   public $temp_name;
   public $random_name;
   public $file_extension;
   public $uploadOk;
   
   
      public function __construct($file,$temp){
        $this->uploaded_file = $file;
		$this->temp_name = $temp;
		
		//searches for the file extennsion in the files' name
         $this->file_extension = pathinfo($this->uploaded_file,PATHINFO_EXTENSION);
    }
	
     //check file extension, validates file then renames it
     public function initUpload(){
		 
		 //rename file
		 $file_name = $this->renameFile();//loads the rename function
	   
	   if(in_array($this->file_extension,$this->authorized_image_extensions)){
		   
		   //set image path
		   $this->destination = $this->path_file[0];
		   $check = getimagesize($this->temp_name);
		   
		   if($check !== false){
			   
			      // Check if file already exists in upload directory
				  if (file_exists($this->destination.$file_name)) {
					return "Sorry, file already exists.<br/>";
				  }
				
				else{
					//Check that the file is not too big, max size 2MB
					if ($_FILES['file']["size"] > 2048000) {
					  return "File is too big";
					}
					
					else{
						//rename file
						 $file_name = $this->renameFile();//loads the rename function
						 
						//initiate upload process
						if(!$this->moveFile() == 0){
							return 'fail';
						}
						
						else{
							 return $file_name;//response when upload and moving process is successful will be the final name of the saved image
						}
					}
					
				}
		   }
			
			
	   }
	   
	   elseif(in_array($this->file_extension,$this->authorized_file_extensions)){
			   //set file path
				$this->destination = $this->path_file[1];
				$check = filesize($this->temp_name);
				
				   if($check !== false){
					   
						  // Check if file already exists in upload directory
						  if (file_exists($this->destination.$file_name)) {
							return "Sorry, file already exists.<br/>";
						  }
						
						else{
							//Check that the file is not too big, max size 2MB
							if ($_FILES['file']["size"] > 2048000) {
							  return "File is too big";
							}
							
							else{
								//initiate upload process
								if(!$this->moveFile() == 0){
									return 'fail';
								}
								
								else{
									 return $file_name;//response when upload and moving process is successful will be the final name of the saved image
								}
							}
							
						}
				   }
		   
	   }
	   else{
		    return $this->error[] = 'The file does not have a valid extension.';
	   }
   }
   
   
   //rename file in this function
   public function renameFile(){
	   $rename_file = md5($this->uploaded_file);//use md5 to create a random 32 characters name file name
	   $this->random_name = str_replace($this->uploaded_file,$this->uploaded_file,$rename_file.'.'.$this->file_extension);
	   
	   return  $this->random_name;
   }
   
   //move file into its directory
   public function moveFile(){
	   
			   if(move_uploaded_file($this->temp_name, $this->destination.$this->random_name)){
				   $this->uploadOK = 1;
			   }
			   
			   else{
					$this->uploadOk = 0 ;
			   }
	       }
   }
