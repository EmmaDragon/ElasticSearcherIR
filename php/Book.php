<?php

class Book{
    
   public $id;
   public $title;
   public $content = "";
   public $dateModified;
   public $dateUploaded;
   public $rank;
   public $size;
   
    public function __construct($idetifier,$title,$dateModified,$dateUploaded,$score,$size) {
     
       $this->id=$idetifier;
       $this->title=$title;
       $this->dateModified=$dateModified;
       $this->rank=$score;
       $this->size=$size;
	   $this->dateUploaded=$dateUploaded;
   }
   
   public function printInfoBook()
   {
       echo $this->id." :: ".$this->title." ( ".strval($this->dateModified)." ) [".strval($this->rank)."]";
       echo "<br>";
       
   }
    
    
}

?>

