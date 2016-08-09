<?php

class HomePage extends TPage
{
	const EASY_LEVEL=10;
	const MEDIUM_LEVEL=5;
	const HARD_LEVEL=3;

	public function setWord($value)
	{
		$this->setViewState('Word',$value,'');
	}

	public function getWord()
	{
		return $this->getViewState('Word','');
	}

	public function getGuessWord()
	{
		return $this->getViewState('GuessWord','');
	}

	public function setGuessWord($value)
	{
		$this->setViewState('GuessWord',$value,'');
	}

	public function setLevel($value)
	{
		$this->setViewState('Level',$value,0);
	}

	public function getLevel()
	{
		return $this->getViewState('Level',0);
	}

	public function setMisses($value)
	{
		$this->setViewState('Misses',$value,0);
	}

	public function getMisses()
	{
		return $this->getViewState('Misses',0);
	}

	public function onSelectLevel($sender,$param)
	{
		if($this->easyLevel->Checked)
			$this->setLevel(self::EASY_LEVEL);
		else if($this->mediumLevel->Checked)
			$this->setLevel(self::MEDIUM_LEVEL);
		else if($this->hardLevel->Checked)
			$this->setLevel(self::HARD_LEVEL);
		else
		{
			$this->startError->setVisible(true);
			return;
		}
		$wordFile=$this->Application->getUserParameter('wordFile');
		$words=preg_split("/[\s,]+/",file_get_contents($wordFile));
		do
		{
			$i=rand(0,count($words)-1);
			$word=$words[$i];
		} while(strlen($word)<5 || !preg_match('/^[a-z]*$/i',$word));
		$word=strtoupper($word);

		$this->setWord($word);
		$this->setGuessWord(str_repeat('_',strlen($word)));
		$this->setMisses(0);
		$this->startPanel->setVisible(false);
		$this->guessPanel->setVisible(true);
	}

	public function onGuessWord($sender,$param)
	{
		$sender->Enabled=false;
		$letter=$sender->Text;
		$word=$this->getWord();
		$guessWord=$this->getGuessWord();
		$pos=0;
		$success=false;
		while(($pos=strpos($word,$letter,$pos))!==false)
		{
			$guessWord{$pos}=$letter;
			$success=true;
			$pos++;
		}
		if($success)
		{
			$this->setGuessWord($guessWord);
			if($guessWord===$word)
			{
				$this->winPanel->setVisible(true);
				$this->guessPanel->setVisible(false);
			}
		}
		else
		{
			$misses=$this->getMisses()+1;
			$this->setMisses($misses);
			if($misses>=$this->getLevel())
				$this->onGiveUp(null,null);
		}
	}

	public function onGiveUp($sender,$param)
	{
		$this->losePanel->setVisible(true);
		$this->guessPanel->setVisible(false);
	}

	public function onStartAgain($sender,$param)
	{
		$this->startPanel->setVisible(true);
		$this->guessPanel->setVisible(false);
		$this->winPanel->setVisible(false);
		$this->losePanel->setVisible(false);
		$this->startError->setVisible(false);
		for($letter=65;$letter<=90;++$letter)
		{
			$guessLink=$this->getChild('guess'.chr($letter));
			$guessLink->setEnabled(true);
		}
	}
}

?>