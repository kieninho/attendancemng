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

        function generateRandomPassword($length = 6) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            $len = strlen($characters);
        
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $len - 1)];
            }
        
            return $randomString;
        }
     }
