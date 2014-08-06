<?php

namespace Jazzyweb\LinkTreeBuilder;

use Jazzyweb\LinkTreeBuilder\Exceptions\URLNotFound;
use Symfony\Component\DomCrawler\Crawler;

class Builder {

    private $title;
    private $link;
    private $depth;
    private $stop;
    private $dump;
    private $timeout;

    public function __construct($title, $link, $depth, $stop = null, $dump = false, $timeout = 1){
        $this->title = $title;
        $this->link = $link;
        $this->depth = $depth;
        $this->stop = $stop;
        $this->dump = $dump;

        ini_set('default_socket_timeout', $timeout);
    }

    public function build(){

        if(!$html = file_get_contents($this->link)){
            throw new URLNotFound("URL not found: " . $this->link);
        }

        $link = new Link($this->title, $this->link);

        $treeLink0 = new TreeLink($link);

        $treeLink = $this->buildTreeLink($treeLink0, $this->depth);

        return $treeLink;

    }

    private function fillChilds(TreeLink $treeLink){

        if(!$html = file_get_contents($treeLink->getLink()->getA())){
            $treeLink->setChilds(array());
            return;
        }
        $finfo = new \finfo(FILEINFO_MIME);
        $mime = $finfo->buffer($html);

        if(!strpos($mime, 'text/html')){
            $treeLink->setChilds(array());
        }

        $crawler = new Crawler($html);
        $a = $crawler->filter("a");
        $attr = $a->extract(array('_text', 'href'));

        $tls = array();
        foreach($attr as $at){
            $title = $at[0];
            $a = $this->correct($at[1], $treeLink->getLink()->getA());
            $link = new Link($title, $a);
//            print_r($a);
//            print_r($at);
//            print_r($link);
            $tl = new TreeLink($link);
            $tls[] = $tl;
        }
        $treeLink->setChilds($tls);

//        return $treeLink;
    }

    private function buildTreeLink(TreeLink $treeLink, $depth){
        if($this->dump){
            echo $treeLink;
        }else{
            echo ".";
        }
        if($depth == 0){
            return $treeLink;
        }else{
            $this->fillChilds($treeLink);
            $depth --;
            foreach($treeLink->getChilds() as $t){
                $this->buildTreeLink($t, $depth);
            }
            return $treeLink;
        }
    }

    private function correct($a, $url){

        $parsedUrl = parse_url($url);

        $rootUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] .  (isset($parsedUrl['port'])? ':' . $parsedUrl['port'] : '');

        if(strpos($a, 'http') === 0){
            $urlCorrected = $a;
        }else {
            $urlCorrected = ((strpos($a, '/') === 0)) ? $rootUrl . $a : $url . '/' . $a;
        }

        return $urlCorrected;
    }
}