<?php
class Helper {
    
    /**
     * Check if hash is equal
     * 
     * @param string $first_hash
     * @param string $second_hash
     */
    public static function hash_equals($first_hash, $second_hash){
        
        if(strlen($first_hash) != strlen($second_hash))
        {
            return false;
        }
        else
        {
            $res = $first_hash ^ $second_hash;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
    
    /**
     * 
     * @param string $url
     * @param number $statusCode
     */
    public static function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
    
    
    /**
     * print a debud variable
     */
    public static function printDebug($variable)
    {
        
        ?>
        <div class="container">
		<?php
       print_r('<pre>');
       print_r($variable);
       print_r('</pre>');
       ?>
    	</div>
    	<?php

    }
}