<?php
/*
 * Created on 31-Jan-2005
 *
 */
class LoadPage extends TPage
{
	public function onInit($param)
	{
		parent::onInit($param);
		if(!$this->isPostBack() && !$this->Module->hasGrants())
		{
			echo "Not enough rights or grants to create Repository";
			exit(-1);       
		}
		
	}

	public function onLoad($param)
	{
		parent::onLoad($param);		
	}

	public function isNotTaken($sender, $param)
	{
		$name = $this->Name->getText();
		$param->isValid=!$this->Module->isTaken($name);
	}

	function onFileUploadFailed($sender,$param){
        switch($this->FileUpload->UploadError) {
            case UPLOAD_ERR_INI_SIZE:
                $message="The uploaded file exceeds the  upload_max_filesize directive in php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message="The uploaded file exceeds the Maxfilesize";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message="The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message="No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message="Missing a temporary folder";
                break;
            default:
                $message="Unknown ErrorCode: " . $this->ImageUpload->UploadError;
        }
       $this->MessageLabel->SetText($message);       
    } 	

	function onFileUpload($sender,$param) {        
        $uploadedFile =  $this->FileUpload->LocalName . ".dump";
        $this->setViewState('uploadedFile',$uploadedFile);       
        copy($this->FileUpload->LocalName, $uploadedFile);          
    } 	

	public function onConfirmBtn($sender, $param)
	{
		require("config.php");
		
		if($this->IsValid && $this->FileUpload->Uploaded)
		{
			//Decrease the number of repositorygrants of this (normal) user
			if(!$this->User->isAdmin())
			{
				$userid = $this->User->getId();
				$grants = $this->Module->getGrants($userid);
				$grants--;
				$this->Module->updateGrants($userid, $grants);
			}

			$name = $this->Name->Text;
      		$description = $this->Description->Text;
			$this->Module->createRepository($this->User->getId(), $name, $description );			

            //Fill the Repository with dump file 
            $uploadedFile = $this->getViewState('uploadedFile');                    		
			exec($svnadmin_cmd." load ".escapeshellarg($svn_repos_loc.DIRECTORY_SEPARATOR.$name)." < ".escapeshellarg($uploadedFile));
			
			//Remove the temporarily dump file
			unlink($uploadedFile);
			
			$this->FormPanel->setVisible(false);
            $this->SuccessPanel->setVisible(true);
			
			//$this->Application->transfer("Repository:AdminPage");			
		}

	}

	public function onCancelBtn($sender, $param)
	{
		$this->Application->transfer("Repository:AdminPage");
	}
	
	public function onGoBack($sender, $param)
	{
		$this->Application->transfer('Repository:AdminPage');
	}
	

}
?>
