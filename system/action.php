<?php

function init(){
     require_once __DIR__.'/app.php';
}

function useQuery($path){
     require_once __DIR__."/query/$path";
}
init();