<?php

function init(){
     require __DIR__.'/app.php';
}

function useQuery($path){
     require __DIR__."/query/$path";
}
init();