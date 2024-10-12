<?php

abstract Class Book{
    protected $id;
    protected $type;
    protected $bookTitle;
    protected $description;
    protected $author;
    protected $date;

    protected $content;

    protected $reader_id;
    protected $numberOfReadings;
    
    public function __construct($id, $bookTitle, $description, $author, $date){
        $this->id = $id;
        $this->bookTitle = $bookTitle;
        $this->description = $description;
        $this->author = $author;
        $this->date = $date;
        $this->content = "";
        $this->numberOfReadings = 0;
            
        }
    public function getInfo():string{
        $result = $this->id.", ". 
        $this->bookTitle.", ".
        $this->description.", ".
        $this->author.", ". 
        $this->date.", ". 
        $this->content.", ". 
        $this->reader_id."";
        
        return $result;
    }

    abstract public function checkOut(int $inReaderId):string;
}

interface RealBook{
    public function getLibraryAddress():string;
}
interface CifralBook{
    public function getLink() :string;
}

Class PaperBook extends Book implements RealBook{
    protected $type = "Paper Book";
    protected $libraryAddress;

    public function __construct($id, $bookTitle, $description, $author, $date, $libraryAddress){
        $this->id = $id;
        $this->bookTitle = $bookTitle;
        $this->description = $description;
        $this->author = $author;
        $this->date = $date;
        $this->content = "";
        $this->numberOfReadings = 0;
        $this->libraryAddress = $libraryAddress;
    }

    public function getLibraryAddress():string{
        return $this->libraryAddress;
    }

    public function checkOut(int $inReaderId): string{
        $this->reader_id = $inReaderId;
        $this->numberOfReadings++;
        return "book is checked out to reader ". $this->reader_id. " Address: ".  $this->getLibraryAddress(). "number of readings: ". $this->numberOfReadings;
    }
}

Class ElectronicBook extends Book implements CifralBook{
    protected $type = "Electronic Book";
    protected $link;

    public function __construct($id, $bookTitle, $description, $author, $date, $link){
        $this->id = $id;
        $this->bookTitle = $bookTitle;
        $this->description = $description;
        $this->author = $author;
        $this->date = $date;
        $this->content = "";
        $this->numberOfReadings = 0;
        $this->link = $link;
    }

    public function getLink(): string{
        return $this->link;
    }

    public function checkOut(int $inReaderId): string{
        $this->reader_id = $inReaderId;
        $this->numberOfReadings++;
        return "book is checked out to reader ". $this->reader_id. " link: ". $this->getLink(). "number of readings: ". $this->numberOfReadings;
    }
}

$books = [
    new PaperBook(0, "Novels", "This novels for kids..", "Piter Worker", "12-09-2013", "Vostriakovskaya st. 6"),

    new ElectronicBook(1, "Home and Work", "This is about you main time in your life...", "Marine Le Pen", 31-12-2012, "https://booker.ru/work_and_home")
];

foreach($books as $book){
    if($book instanceof RealBook){
        echo $book->getLibraryAddress(). "\n";
    }
    if($book instanceof CifralBook){
        echo $book->getLink(). "\n";
    }
}


echo $books[0]->checkOut(10). "\n";
echo $books[1]->checkOut(25). "\n";

echo $books[0]->checkOut(40). "\n";
echo $books[1]->checkOut(56). "\n";



//docker run --rm -v ${pwd}:/cli php:8.2-cli php /cli/book.php