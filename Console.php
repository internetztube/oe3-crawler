<?php

class Console {
    
    private static $lineLength = 60;
    private static $lineChar = '-';
    private static $doubleLineChar = '=';

    public static function write($string) {
        echo '| ';
        echo $string;
        
        if(mb_strlen($string) < self::$lineLength) {
            for($i = 0; $i < self::$lineLength - mb_strlen($string) - 2; $i++) {
                echo ' ';
            }
            echo ' |';
        }
        self::newLine();
    }

    public static function line($doubleLine = false) {
        echo '+';

        for($i = 0; $i < self::$lineLength; $i++) {
            if($doubleLine) {
                echo self::$doubleLineChar;
            } else {
                echo self::$lineChar;
            }
        }
        echo '+';
        self::newLine();
    }

    public static function newLine() {
        echo "\n";
    }
}