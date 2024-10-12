<?php 
// class A {
//     public function foo() {
//     static $x = 0;
//     echo ++$x;
//     }
// }
//     $a1 = new A();
//     $a2 = new A();
//     $a1->foo(); //1
//     $a2->foo(); //2
//     $a1->foo(); //3
//     $a2->foo(); //4 
//Выдает 1234 так как $x - статическая переменная и обращение идет к одной и той же ячейке памяти.

class A {
    public function foo() {
    static $x = 0;
    echo ++$x;
    }
}
class B extends A {
}
    $a1 = new A();
    $b1 = new B();
    $a1->foo(); //1
    $b1->foo(); //2 - хотя должен был вывести 1
    $a1->foo(); //3 - хотя должен был вывести 2
    $b1->foo(); //4 - хотя должен был вывести 2

    //На версии PHP 8.2 Выдает почему-то тоже самое - 1234. Из-за того, что переменная $x - статическая. Хотя при наследовании все таки должна была создаться новая переменная.
    //На версии PHP 7.4 выдает, согласно теории 1122

// PS D:\Geekbrains\PHP\homework4> docker run --rm -v ${pwd}:/cli php:8.2-cli php /cli/task6.php
// 1234
// PS D:\Geekbrains\PHP\homework4> docker run --rm -v ${pwd}:/cli php:7.4-cli php /cli/task6.php
// Unable to find image 'php:7.4-cli' locally
// 7.4-cli: Pulling from library/php
// 30f377be4678: Download complete
// c77004105467: Download complete
// d3e4898bfd25: Download complete
// Digest: sha256:620a6b9f4d4feef2210026172570465e9d0c1de79766418d3affd09190a7fda5
// Status: Downloaded newer image for php:7.4-cli
// 1122
// PS D:\Geekbrains\PHP\homework4>


    //docker run --rm -v ${pwd}:/cli php:8.2-cli php /cli/task6.php