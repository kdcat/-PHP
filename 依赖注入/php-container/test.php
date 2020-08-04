<?php
require_once('./Container.php');

use KDCAT\Container\Container;
Class A {
    public function __construct(B $b)
    {
        $this->echo();
    }
    private function echo(){
        echo "AAAA";
    }
}
Class B {
    public function __construct(C $b, D $d)
    {
        $this->echo();
        $d->echo();
    }
    private function echo(){
        echo "BBBB";
    }
}
Class C {
    public function __construct()
    {
        $this->echo();
    }
    private function echo(){
        echo "CCCC";
    }
}
Class D {
    public function echo(){
        echo "DDDD";
    }
}
$container = new Container();
$container->set('a', 'A');
var_dump($container->get('a'));exit;