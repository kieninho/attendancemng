<?php
    namespace App\Services;

     class helper {
        public static function genCode($prefix,$listExitsCode) {
            $code = 0;
            $exists = true;
            while ($exists) {
                $code = rand(1, 9999);
                var_dump($code);
                echo "<br>"."<br>"."<br>";
                var_dump($listExitsCode);

                if (!in_array($code, $listExitsCode)) {
                    $exists = false;
                }
            }
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
            return $prefix.$code;
        }
     }
