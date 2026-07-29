<?php


function dd(){
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller = $trace[1] ?? null;
    echo "<pre>";
        if(!is_null($caller)) {
            echo $trace[1]['file'] . " линия №" . $trace[1]['line'].PHP_EOL;
            echo 'Class:'.((isset($trace[1]['class']))?$trace[1]['class']:'') .'=>' .$trace[1]['function'].PHP_EOL;
        }
        echo var_export(func_get_args(),true);
    echo "<pre>";
}
function d(){
    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    $caller = $trace[1] ?? null;
    echo "<pre>";
    if(!is_null($caller)) {
        echo $trace[1]['file'] . " линия №" . $trace[1]['line'].PHP_EOL;
        echo 'Class:'.((isset($trace[1]['class']))?$trace[1]['class']:'') .'=>' .$trace[1]['function'].PHP_EOL;
    }
    echo var_export(func_get_args(),true);
    echo "<pre>";
    die();
}