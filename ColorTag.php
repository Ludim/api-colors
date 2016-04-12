<?php
/*
 * 1 - Obtener el color dado por la API
 * 2 - Pasar el RGB a HSV
 * 3 - Buscar en la tabla ColorsNames
 * 4 - Establecer el color del id_elemento en la tabla
 */

class ColorTag {
    public function getColorByAPI($link)
    {
        
    }
    /*
     * http://stackoverflow.com/questions/15202079/convert-hex-color-to-rgb-values-in-php
     */
    public function hex2rgb($hex_color)
    {
        try {
            if($hex_color == "#" or strlen($hex_color) < 2) {
                throw new Exception("It's not a RGB hex code");
            }
            if(substr($hex_color, 0,1) != "#") {
                throw new Exception("Verify hex code starts with \"#\"");
            }
            $_rgb = sscanf($hex_color, "#%02x%02x%02x");
            $rgb["r"] = $_rgb[0];
            if(empty($_rgb[1]))     {    $rgb["g"] = 0;    $rgb["b"] = 0; } 
            elseif(empty($_rgb[2])) {    $rgb["b"] = 0;   }
            else { $rgb["g"] = $_rgb[1]; $rgb["b"] = $_rgb[2];}
            unset($_rgb);
            return $rgb;
        } catch(Exception $e) {
            print "Caught exception: ". $e->getMessage()."\n";
            exit();
        }
    }
    /*
     * Calculate HSV = Hue, Saturation (%), Value (%)
     */
    private function getHue($_rgb, $delta)
    {
        try {
            if($delta == 0) {
                return 0;
            }
            $max_key = array_search(max($_rgb), $_rgb);
            $_r = $_rgb["r"];   $_g = $_rgb["g"];   $_b = $_rgb["b"];
            if ($max_key == "r") {
                $_h = (($_g - $_b) / $delta);
            } elseif ($max_key == "g") {
                $_h = 2 + (($_b - $_r) / $delta);
            } elseif ($max_key == "b") {
                $_h = 4 + (($_r - $_g) / $delta);
            } else {
                throw new Exception("Error unknown");
            }
            $_h = $_h * 60;
            return $_h < 0 ? round($_h + 360) : round($_h);
        } catch(Exception $e) {
            print "Caught exception: ". $e->getMessage()."\n";
            exit();
        }
    }
    private function getSaturation($_rgb, $delta)
    {
        return (max($_rgb) == 0) ? 0 : round(($delta / max($_rgb)) * 100, 1);
    }
    private function getValue($_rgb)
    {
        return round(max($_rgb) * 100, 1);
    }
    public function rgb2hsv($rgb)
    {
        try {
            if(!is_array($rgb)) {
                throw new Exception("It must be an array");
            }
            if(empty($rgb)) {
                throw new Exception("Empty array");
            }
            $divide_255 = function($value) { return $value / 255; };
            $_rgb = array_map($divide_255, $rgb);
            $delta = max($_rgb) - min($_rgb);
            $hsv["h"] = $this->getHue($_rgb, $delta);
            $hsv["s"] = $this->getSaturation($_rgb, $delta);
            $hsv["v"] = $this->getValue($_rgb);
            return $hsv;
        } catch(Exception $e) {
            print "Caught exception: ". $e->getMessage()."\n";
            exit();
        }
    }

}

$ct = new ColorTag();
$hex_color = "#9E2C30";
#$hex_color = "#fffff";
$rgb = $ct->hex2rgb($hex_color);
print_r($rgb);
print_r($ct->rgb2hsv($rgb));
