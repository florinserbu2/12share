<?php 
class Fd_Uploader extends Fd_Object{
    
	const SMALL_SIZE = "150x150";
	const MEDIUM_SIZE = "400x400";
	const LARGE_SIZE = "1024x768";
	
    protected $_manager;
    protected $_fields_number;
    protected $_field_label;
     
    protected $_upload_path;
    protected $_upload_url;
    public $errors;
    
    public function __construct(Fd_ManagerAbstract $manager, $fields_number = 1){
        $this->_fields_number = $fields_number;
        $this->_manager = $manager;
        $this->_field_label = ucfirst(Fd_Language_Labels::get('files'));
        
        if($manager->hasRow() && $row = $manager->getRow()){
        	$intermediate_path = $row->id;
			$entity_name = $manager->getEntityName();
        }else{
        	$intermediate_path = rand(0, 99999999);
			$entity_name = "temp";
        }
		
    	$this->_upload_path = self::getUploadPath($entity_name, $intermediate_path, "path");
        $this->_upload_url = self::getUploadPath($entity_name, $intermediate_path, "url");
        
        $this->errors = array();
    }
    
    public static function getUploadPath($entity_name, $entity_id, $type = 'path'){
        $config = Zend_Registry::get('config');
        $static = $config['static'];
		
        $ret = rtrim($static[$type],"/")."/uploads/".$entity_name.'/'.$entity_id;
		if($type == 'path'){
			return $ret;
		}
		return $ret;
		
    }
    
    public function setFieldLabel($label){
        $this->_field_label = Fd_Language_Labels::get($label);
    }
    
	public function removeContent(){
		if(!$this->_manager->hasRow()){
			throw new Fd_Exception("No row for removeContent");
		}
		return self::rmdirr($this->_upload_path);
	}
	
    public function form(&$form = null, $is_ajax_upload = false){
        
        $form = $form ? $form : new Fd_Twitter_Form();
        $form->setAttrib("enctype", 'multipart/form-data');	
		
        for($i = 0 ; $i < $this->_fields_number; $i++){
            if($is_ajax_upload){	
            	$element = new Fd_Twitter_Form_Element_AjaxFile('upload_'.$i);
				$decorator = array('AjaxFile') + Fd_Twitter_Form::$base_decorators;
				
			}else{
				$element = new Zend_Form_Element_File('upload_'.$i);
				$decorator = array('File') + Fd_Twitter_Form::$base_decorators;
				
			}
            $element->setBelongsTo("uploads");
			$element->setValueDisabled(true);
            $element->setLabel($this->_field_label." #".$i);
            $element->setDecorators($decorator);
            $form->addElement($element);
        }
        
        return $form;
    }
    
    public function getUploadUrl(){
        return $this->_upload_url;
    }
    
	public function fbUpload($file){
		$image = new Imagick();
						  
		$c = new Zend_Http_Client();
		$c->setUri($file);
		$result = $c->request('GET');        
		$image->readimageblob($result->getBody());
			
			
		$size = $image->getimagesize();
		$type = $image->getimageformat();
		
		if($type == "JPEG"){
			$type = "JPG";
		}
		$type = strtolower($type);
		
		$name = 'fb-image.'.$type;
		
		$datas = array();
		
		$file = $this->__processImage($image, $name);
		$file->size = $size;
	    $file->type = $name;
	    
		$datas[] = $file;
		
        return $this->_manager->editContent($datas);
	}
	
	public function processAjaxUploads($post){
		
		if(!$this->_manager->hasRow()){
			throw new Fd_Exception("Manager has no row for processAjaxUploads, can't know the id in order to create the row");
		}
			
		//get only what we need from post
		$need = array();
		for($i = 0; $i < $this->_fields_number; $i++){
			$need[] = "upload_".$i;
		}
		foreach($post as $k => $v){
			if(!in_array($k, $need)){
				unset($post[$k]);	
			}
		}
		
		//clean the posted data	
		$this->_manager->sanitize($post);
		
		//create the dir
		$this->makeDir(true);
		
		//copy each uploaded file into the manager row's dir
		$complete = true;
		foreach($post as $k => $v){
			if($v){ 
				//copy the 150 img
				$small_uploaded = $this->__storeAjaxUploaded($v, self::SMALL_SIZE, $k);
				
				//copy the 400 img
				$med_uploaded = $this->__storeAjaxUploaded($v, self::MEDIUM_SIZE, $k);
				
				//copy the 1024 img
				$large_uploaded = $this->__storeAjaxUploaded($v, self::LARGE_SIZE, $k);
				
				if(!($small_uploaded && $med_uploaded && $large_uploaded)){
					$complete = false;
				}
			}
		}
		if($complete){
			$this->rmdirr($v);
		}
	}
	
	
	private function __storeAjaxUploaded($temp_path, $size, $input){
		$size_path = $temp_path.'/'.$size;
		if(!is_dir($size_path)){
			throw new Fd_Exception("No ".$size_path." DIR on temp dir".$temp_path);
		}
		$dir = opendir($size_path);
		while($file = readdir($dir)){
			if(!in_array($file, array(".", ".."))){
				if(rename($size_path."/".$file, $this->_upload_path."/".$size."/".$file)){
				
					$file_ob = new stdClass();
					$file_ob->name = $file;
					 
			        $file_ob->size = filetype($this->_upload_path."/".$size."/".$file);
			        $file_ob->type = filesize($this->_upload_path."/".$size."/".$file);
			    	$file_ob->path = $this->_upload_path;
			    	$file_ob->url = $this->_upload_url.'/'.$size.'/'.$file;
					$file_ob->input = $input;
					
					$datas[] = $file_ob;
				}
			}
        }
       
		if($this->_manager->hasRow() && @$datas){
			return $this->_manager->editContent($datas);
		}
	}
	
	/**
	 * Creates the dir throwing an exception if fails
	 */
	private function makeDir($with_sizes = false){
		 if (!is_dir($this->_upload_path)){
            if(!mkdir($this->_upload_path, 0755, true)){
                throw new Fd_Exception("directory can't be created {$this->_upload_path}");
            }
        }
		 
		if($with_sizes){
			$size3_path = $this->_upload_path."/".self::SMALL_SIZE;
        	$size2_path = $this->_upload_path."/".self::MEDIUM_SIZE;
   			$size1_path = $this->_upload_path."/".self::LARGE_SIZE;
       		
			if(!mkdir($size1_path, 0755, true)){
                throw new Fd_Exception("directory $size1_path can't be created {$this->_upload_path}");
            }
			if(!mkdir($size2_path, 0755, true)){
                throw new Fd_Exception("directory $size2_path can't be created {$this->_upload_path}");
            }
			if(!mkdir($size3_path, 0755, true)){
                throw new Fd_Exception("directory $size3_path can't be created {$this->_upload_path}");
            }
		} 
		return $this->_upload_path;
		
	}
	
    public function upload($file = null){
        $adapter = new Zend_File_Transfer_Adapter_Http();
		
        $this->makeDir();
        $adapter->setDestination($this->_upload_path); 
        $adapter->addValidator(new Fd_Validate_File_IsImage(), true);
   
		$files = $adapter->getFileInfo();
		$datas = array();
        foreach ($files as $file => $info) {
            $name = $adapter->getFileName($file);
            // file uploaded & is valid
            if (!$adapter->isUploaded($file)) {
                foreach($adapter->getErrors() as $k => $v){
                    $this->errors[$file] = $v;
                }
                continue;
            }
            if (!$adapter->isValid($file)){
                $this->errors[$file] = $adapter->getMessages();
                continue;
            }
        
            // receive the files into the user directory
            //$adapter->receive($file); // this has to be on top
        	if(!class_exists("Imagick")){
        		throw new Fd_Exception("Imagick class not exists, at ".date("Y-m-d H:i:s", time()));
        	}else{
	        	$image = new Imagick($info['tmp_name']);
				$name = rand(1,999).".".$image->getimageformat();
				$filename = $file;
	            $file = $this->__processImage($image, $name);
				 
		        $file->size = $adapter->getFileSize($info['name']);
		        $file->type = $adapter->getMimeType($info['name']);
		    	$file->path = $this->_upload_path;
		    	$file->url = $this->_upload_url.'/150x150/'.$file->name;
				$file->input = $filename;
				
				$datas[] = $file;
			}
        }
        if($this->errors || !@$datas){
            return false;
        }
		if($this->_manager->hasRow()){
        	return $this->_manager->editContent($datas);
		}else{
			return $datas;
		}
    }

	private function __processImage(Imagick $image, $name){
		//the imagik object
        $height = $image->getimageheight();
        $width = $image->getimagewidth();
        $size3_path = $this->_upload_path."/150x150";
        $size2_path = $this->_upload_path."/400x400";
        $size1_path = $this->_upload_path."/1024x768";
        
            
        if(($width >= 1024 && $height >= 768)){
            $image->resizeImage(1024,768,Imagick::FILTER_LANCZOS,1,true);
            @mkdir($size1_path, 0755, true);
            $image->writeimage($size1_path."/".$name);
            
            $image->resizeImage(400,400,Imagick::FILTER_LANCZOS,1,true);
            @mkdir($size2_path, 0755, true);
            $image->writeimage($size2_path."/".$name);
            
            $image->resizeImage(150,150,Imagick::FILTER_LANCZOS,1,true);
            @mkdir($size3_path, 0755, true);
            $image->writeimage($size3_path."/".$name);
            
                
        }elseif($width >= 1024 && $height < 768){
            $image->adaptiveResizeImage(1024,0);
            @mkdir($size1_path, 0755, true);
            $image->writeimage($size1_path."/".$name);
            
            $image->adaptiveResizeImage(400,0);
            @mkdir($size2_path, 0755, true);
            $image->writeimage($size2_path."/".$name);
            
            $image->adaptiveResizeImage(150,0);
            @mkdir($size3_path, 0755, true);
            $image->writeimage($size3_path."/".$name);
        }elseif($width < 1024 && $height > 768){
            $image->adaptiveResizeImage(0,768);
            @mkdir($size1_path, 0755, true);
            $image->writeimage($size1_path."/".$name);
            
            $image->adaptiveResizeImage(0,400);
            @mkdir($size2_path, 0755, true);
            $image->writeimage($size2_path."/".$name);
            
            $image->adaptiveResizeImage(0,150);
            @mkdir($size3_path, 0755, true);
            $image->writeimage($size3_path."/".$name);
        }else{
            @mkdir($size1_path, 0755, true);
			$image->writeimage($size1_path."/".$name);
            
           $image->resizeImage(400,400,Imagick::FILTER_LANCZOS,1,true);
            @mkdir($size2_path, 0755, true);
            $image->writeimage($size2_path."/".$name);
            
            $image->resizeImage(150,150,Imagick::FILTER_LANCZOS,1,true);
            @mkdir($size3_path, 0755, true);
            $image->writeimage($size3_path."/".$name);
        }
            
        $fileclass = new stdClass();
    
        // we stripped out the image thumbnail for our purpose, primarily for security reasons
        // you could add it back in here.
        $fileclass->name = str_replace($this->_upload_path,"", $name);
        $fileclass->name = trim($fileclass->name, "/");
        $fileclass->name = trim($fileclass->name, '\\');
       
        return $fileclass;
	}

    public function getErrors(){
        if(!empty($this->errors)){
            return $this->errors;
        }else{
            return false;
        }
    }
	
	public static function rmdirr($dirname)
	{
	    // Sanity check
	    if (!file_exists($dirname)) {
	        return false;
	    }
	  
	    // Simple delete for a file
	    if (is_file($dirname) || is_link($dirname)) {
	        return unlink($dirname);
	    }
	  
	    // Loop through the folder
	    $dir = dir($dirname);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }
	  
	        // Recurse
	        self::rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
	    }
	  
	    // Clean up
	    $dir->close();
	    return rmdir($dirname);
	}
}
?>