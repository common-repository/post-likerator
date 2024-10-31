<?php

// utility functions for nice looking print_r and var_dump
if (!function_exists('pr')){
  function pr($value, $title = '', $exit = false, $position = "relative"){
    echo '<pre style="box-sizing: border-box; clear: both; position: '.$position.'; z-index: 9999; width: 90%; overflow: scroll; color: #333333; background: #eeeeee; margin: 1em 5%; padding: 2em 1em 1em 1em; border: 2px solid #aaaaaa; border-radius: 5px; opacity: 0.75; transition: all 0.3s ease" onMouseOver="this.style.opacity=1" onMouseOut="this.style.opacity=0.75">';
    if ($title != ''){
      echo '<strong style="box-sizing: border-box; position: absolute; left: 0; top: 0; display: block; width: 100%; line-height: 1.66em; padding: 0 1em; background: #aaaaaa; color: #ffffff;">'.$title.'</strong>';
    }
    print_r($value);
    echo '</pre>';

    if ($exit === true){
      exit();
    }
  }
}

if (!function_exists('vd')){
  function vd($value, $title = '', $exit = false, $position = "relative"){
    echo '<pre style="box-sizing: border-box; clear: both; position: '.$position.'; z-index: 9999; width: 90%; overflow: scroll; color: #333333; background: #eeeeee; margin: 1em 5%; padding: 2em 1em 1em 1em; border: 2px solid #aaaaaa; border-radius: 5px; opacity: 0.75; transition: all 0.3s ease" onMouseOver="this.style.opacity=1" onMouseOut="this.style.opacity=0.75">';
    if ($title != ''){
      echo '<strong style="box-sizing: border-box; position: absolute; left: 0; top: 0; display: block; width: 100%; line-height: 1.66em; padding: 0 1em; background: #aaaaaa; color: #ffffff;">'.$title.'</strong>';
    }
    var_dump($value);
    echo '</pre>';

    if ($exit === true){
      exit();
    }
  }
}