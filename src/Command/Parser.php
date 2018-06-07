<?php
namespace Command;

class Parser
{
    public static $args;

    /**
     * parse - Parse Server argv to args
     *
     * $args = PHPCommand::parse($_SERVER['argv']);
     * @param [type] $argv
     * @return void
     */
    public static function parse($argv = null)
    {
        $argv = $argv ? $argv : $_SERVER['argv'];

        array_shift($argv);
        $out = [];

        $total_argv = count($argv);

        foreach($argv as $i => $arg){
            ##### TYPE1: --foo --bar=baz
            if(substr($arg,0,2) === '--'){
                //Check --bar=baz
                $pos = strpos($arg, '=');

                //--foo
                if($pos === false){
                    $key = substr($arg, 2);

                    // --foo value
                    if($i + 1 < $total_argv  &&  $argv[$i + 1][0] !== '-'){
                        $value = $argv[$i + 1];
                    }else{
                        $value = isset($out[$key]) ? $out[$key] : true;
                    }
                    $out[$key] = $value;
                }
                // --foo=bar
                else{
                    $key       = substr($arg, 2 , $pos -2);
                    $value     = substr($arg, $pos + 1);
                    $out[$key] = $value;
                }
            }

            #### TYPE2: -k=value -abc
            else if(substr($arg, 0 , 1) === '-'){
                // -k=value
                if(substr($arg, 2, 1) === '='){
                    $key       = substr($arg, 1, 1);
                    $value     = substr($arg, 3);
                    $out[$key] = $value;
                }
                // -abc
                else{
                    $chars = str_split(substr($arg, 1));
                    foreach($chars as $char){
                        $key       = $char;
                        $value     = isset($out[$key]) ? $out[$key] : true;
                        $out[$key] = $value;
                    }
                    // -a value1 -abc value2
                    if($i + 1 < $total_argv  &&  $argv[$i + 1][0] !== '-'){
                        $out[$key] = $argv[$i + 1];
                    }
                }
            }

            #### TYPE3: Plain arguments
            else{
                $value = $arg;
                $out[] = $value;
            }
        }

        self::$args = $out;

        return $out;
    }
}