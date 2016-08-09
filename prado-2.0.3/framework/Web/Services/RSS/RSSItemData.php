<?php
require_once(dirname(__FILE__).'/RSSBase.php');

class RSSItemData extends RSSBase {
	
	protected $about;
	protected $title;
	protected $link;
	protected $description;
	protected $subject;
	protected $date;
	protected $author;
	protected $comments;
	protected $image;
	
	function __construct($about = '', 
						 $title = '', 
						 $link = '', 
						 $description = '',
						 $subject = '',	
						 $date = 0,	
						 $author = '', 
						 $comments = '',
						 $image = '') {
		$this->setVar($about, 'about', 'string');
		$this->setVar($title, 'title', 'string');
		$this->setVar($link, 'link', 'string');
		$this->setVar($description, 'description', 'string');
		$this->setVar($subject, 'subject', 'string');
		$this->setVar($date, 'date', 'int');
		$this->setVar($author, 'author', 'string');
		$this->setVar($comments, 'comments', 'string');
		$this->setVar($image, 'image', 'string');
	} // end constructor

	public function getAbout() {
		return $this->getVar('about');
	} // end function

	public function getTitle() {
		return $this->getVar('title');
	} // end function
	
	public function getLink() {
		return $this->getVar('link');
	} // end function	
	
	public function getDescription() {
		return $this->getVar('description');
	} // end function	
	
	public function getSubject() {
		return $this->getVar('subject');
	} // end function		
	
	public function getItemDate() {
		return $this->getVar('date');
	} // end function		
	
	public function getAuthor() {
		return $this->getVar('author');
	} // end function		
	
	public function getComments() {
		return $this->getVar('comments');
	} // end function		
	
	public function getImage() {
		return $this->getVar('image');
	} // end function		
} // end class
?>