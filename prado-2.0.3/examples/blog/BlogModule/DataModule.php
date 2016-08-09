<?php

using('System.Data');

class DataModule extends TModule
{
	public function onLoad($param)
	{
		parent::onLoad($param);
		$this->Database->setDataSourceName($this->Application->getUserParameter('DSN'));
	}
	
	public function selectBlog($id=null)
	{
		if(is_null($id))
		{
			$result=$this->Database->Execute("SELECT * FROM tblBlog ORDER BY wtime DESC");
			return $result;
		}
		else
		{
			$result=$this->Database->Execute("SELECT * FROM tblBlog WHERE id=$id");
			return $result->RecordCount()>0?$result->fields:null;
		}
	}

	public function createBlog($blog)
	{
		$blog=pradoEscapeQuotes($blog);
		$author=$this->User->getUsername();
		$wtime=time();
		return $this->Database->Execute("INSERT INTO tblBlog (author,title,content,wtime) VALUES ('$author','{$blog['title']}','{$blog['content']}',$wtime)");
	}

	public function updateBlog($id,$blog)
	{
		$blog=pradoEscapeQuotes($blog);
		$wtime=time();
		return $this->Database->Execute("UPDATE tblBlog SET title='{$blog['title']}', content='{$blog['content']}', wtime=$wtime WHERE id=$id");
	}

	public function canUpdateBlog($id,$username=null)
	{
		if(is_null($username))
			$username=$this->User->getUsername();
		$result=$this->Database->Execute("SELECT * FROM tblBlog WHERE id=$id AND author='$username'");
		return $result->RecordCount()>0;
	}

	public function deleteBlog($id)
	{
		return $this->Database->Execute("DELETE FROM tblBlog WHERE id=$id");
	}
}

?>