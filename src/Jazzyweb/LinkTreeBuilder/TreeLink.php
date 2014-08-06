<?php

namespace Jazzyweb\LinkTreeBuilder;


class TreeLink {

    private $link;
    private $childs = array();

    /**
     * @param Link $link
     * @param array $childs
     */
    public function __construct(Link $link, $childs = null){
        $this->link = $link;
        $this->childs = $childs;
    }

    /**
     * @param \Jazzyweb\LinkTreeBuilder\Link $link
     */
    public function setLink(Link $link)
    {
        $this->link = $link;
    }

    /**
     * @return \Jazzyweb\LinkTreeBuilder\Link
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param array $childs
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
    }

    /**
     * @return array
     */
    public function getChilds()
    {
        return $this->childs;
    }

    public function __toString(){
        $str = "--------------------START NODE--------------------" . PHP_EOL;
        $str .= "title: " . $this->getLink()->getTitle() . PHP_EOL;
        $str .= "link: " . $this->getLink()->getA() .PHP_EOL;
        if(!is_null($this->getChilds())){
            foreach($this->getChilds() as $tree)
                $str.= $tree->__toString();
        }
        $str .= "--------------------END NODE---------------------" . PHP_EOL;
        return $str;

    }

    public function toArray(){

        $result['title'] = $this->getLink()->getTitle();
        $result['url'] = $this->getLink()->getA();
        if(!is_null($this->getChilds()))
        {
            foreach($this->getChilds() as $child){
                $result['childs'][] = $child->toArray();
            }
        }

        return $result;
    }
}