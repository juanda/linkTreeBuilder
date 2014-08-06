<?php

namespace Jazzyweb\LinkTreeBuilder;


class Link {

    private $title;
    private $a;

    public function __construct($title, $a){
        $this->title = $title;
        $this->a = $a;
    }
    /**
     * @param mixed $link
     */
    public function setA($a)
    {
        $this->a = $a;
    }

    /**
     * @return mixed
     */
    public function getA()
    {
        return $this->a;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }
}