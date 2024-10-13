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
    public function getId(){
        return $this->id;
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


class Shelf {
    public  $books = [];

    public $id;

    public function __construct($id){
        $this->id = $id;
    }
    
    public function installBook(Book $book): void{
       // array_push($this->books, $book);
       $this->books[] = $book;
    }
    public function uninstallBook(int $bookId): void{
        foreach($this->books as $key => $value){
            if($value->getId() == $bookId){
                unset($this->books[$key]);
            }
        }
    }
    public function getBooks(): array{
        return $this->books;
    }
    public function getId(): int{
        return $this->id;
    }
}


class Room{
    public $id;
    public $shelves = [];
    public function __construct($id, $shelfCount){
        $this->id = $id;
        for($i = 0; $i < $shelfCount; $i++){
            $this->shelves[] = new Shelf($i);
        }
    }
    public function addBook(Book $book, $shelfId): void{
        foreach($this->shelves as $key => $value){
            if($value->getId() == $shelfId){
                $this->shelves[$key]->installBook($book);
            }
        }
    }

    public function removeBook(int $bookId): void{
        foreach($this->shelves as &$shelf){
            $shelf->uninstallBook($bookId);
        }
    }
}


$books = [
    new PaperBook(0, "Novels", "This novels for kids..", "Piter Worker", "12-09-2013", "Vostriakovskaya st. 6"),

    new PaperBook(1, "Drink Water", "This about drinking..", "Piter Pen", "12-11-2013", "Vostriakovskaya st. 6"),

    new ElectronicBook(2, "Home and Work", "This is about you main time in your life...", "Marine Le Pen", 31-12-2012, "https://booker.ru/work_and_home")
];


$books2 = [
    new PaperBook(3, "Fantasy", "This novels for kids..", "Piter Worker", "12-09-2013", "Vostriakovskaya st. 6"),

    new PaperBook(4, "Go and sleep", "This about drinking..", "Piter Pen", "12-11-2013", "Vostriakovskaya st. 6"),

    new ElectronicBook(5, "Fly", "This is about you main time in your life...", "Marine Le Pen", 31-12-2012, "https://booker.ru/fly")
];

echo "Электронная и бумажная книга: \n";

foreach($books as $book){
    if($book instanceof RealBook){
        echo $book->getLibraryAddress(). "\n";
    }
    if($book instanceof CifralBook){
        echo $book->getLink(). "\n";
    }
}


echo "Выдача книг: \n";

echo $books[0]->checkOut(10). "\n";
echo $books[1]->checkOut(25). "\n";
echo $books[2]->checkOut(27). "\n";

echo $books[0]->checkOut(40). "\n";
echo $books[1]->checkOut(56). "\n";
echo $books[2]->checkOut(58). "\n";

echo "Книги на полке (Агрегация): \n";

$shelf = new Shelf(0);
print_r($shelf->getBooks() );

echo "Установка книг на полку \n";

foreach($books as $book){
    if($book instanceof RealBook){
        $shelf->installBook($book);
    }
}

print_r($shelf->getBooks() );

echo "Удаление книги с полки \n";

$shelf->uninstallBook(0);

print_r($shelf->getBooks() );

echo "Создание комнаты с полками и заполнение книгами \n";

$room = new Room(0,2);

foreach($books as $book){
    if($book instanceof RealBook){
        $room->addBook($book, 0);
    }
}

foreach($books2 as $book){
    if($book instanceof RealBook){
        $room->addBook($book, 1);
    }
}

print_r($room);

echo "Удаление книги \n";

$room->removeBook(0);

print_r($room);

//docker run --rm -v ${pwd}:/cli php:8.2-cli php /cli/book.php