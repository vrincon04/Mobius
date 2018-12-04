<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('create_captcha'))
{
    /**
     * Create CAPTCHA
     *
     * @param	array|string	$data		data for the CAPTCHA
     * @param	string	$img_path	path to create the image in
     * @param	string	$img_url	URL to the CAPTCHA image folder
     * @param	string	$font_path	server path to font
     * @return	string|array
     */
    function create_captcha($data = '', $img_path = '', $img_url = '', $font_path = '')
    {
        $colors_def_bg = array(
            array(137,219,236),
            array(255,208,141),
            array(254,171,185),
            array(207,151,215),
            array(212,212,212)
        );

        $colors_def_text = array(
            array(0,104,132),
            array(237,0,38),
            array(176,0,81),
            array(110,0,108),
            array(0,0,0)
        );

        $defaults = array(
            'word'		=> '',
            'img_path'	=> '',
            'img_url'	=> '',
            'img_width'	=> '250',
            'img_height'	=> '40',
            'font_path'	=> '',
            'expiration'	=> 7200,
            'word_length'	=> 8,
            'font_size'	=> 30,
            'img_id'	=> '',
            'colors'	=> array(
                'background'	=> array(255,255,255),
                'border'	=> array(153,102,102),
                'text'		=> $colors_def_text[mt_rand(0, count($colors_def_text) - 1)],
                'grid'		=> $colors_def_bg[mt_rand(0, count($colors_def_bg) - 1)]
            )
        );

        foreach ($defaults as $key => $val)
        {
            if ( ! is_array($data) && empty($$key))
            {
                $$key = $val;
            }
            else
            {
                $$key = isset($data[$key]) ? $data[$key] : $val;
            }
        }

        if ($img_path === '' OR $img_url === ''
            OR ! is_dir($img_path) OR ! is_really_writable($img_path)
            OR ! extension_loaded('gd'))
        {
            return FALSE;
        }

        // -----------------------------------
        // Remove old images
        // -----------------------------------

        $now = microtime(TRUE);

        $current_dir = @opendir($img_path);
        while ($filename = @readdir($current_dir))
        {
            if (in_array(substr($filename, -4), array('.jpg', '.png'))
                && (str_replace(array('.jpg', '.png'), '', $filename) + $expiration) < $now)
            {
                @unlink($img_path.$filename);
            }
        }

        @closedir($current_dir);

        // -----------------------------------
        // Do we have a "word" yet?
        // -----------------------------------

        if (empty($word))
        {
            $word = '';
            $operations = array(
                '+',
                '-',
                '*'
            );

            $numbers = array(
                0	=> 'cero',
                1	=> 'uno',
                2	=> 'dos',
                3	=> 'tres',
                4	=> 'cuatro',
                5	=> 'cinco',
                6	=> 'seis',
                7	=> 'siete',
                8	=> 'ocho',
                9	=> 'nueve',
                '+'	=> 'mas',
                '-'	=> 'menos',
                '*' => 'por'
            );

            $o1 = $operations[array_rand($operations, 1)];
            switch ($o1) {
                case '+':
                    $n1 = rand(1, 9);
                    $n2 = rand(1, 9);
                    break;
                case '-':
                    $n1 = rand(2, 9);
                    $n2 = rand(1, $n1);
                    break;
                case '*':
                    $n1 = rand(1, 9);
                    $n2 = rand(1, 5);
                    break;
                default:
                    $n1 = rand(1, 9);
                    $n2 = rand(1, 9);
                    break;
            }

            $operation = "$n1 $o1 $n2";

            $result = NULL;
            eval('$result = ' . $operation . ';');

            $texto = rand(1, 4);

            switch ($texto) {
                case 1:
                    $n1 = $numbers[$n1];
                    break;
                case 2:
                    $n2 = $numbers[$n2];
                    break;
                case 3:
                    $o1 = $numbers[$o1];
                    break;
                default:
                    # code...
                    break;
            }
            $o1 = ($o1 === '*') ? 'x' : $o1;
            $word = "$n1 $o1 $n2";
        }

        // -----------------------------------
        // Determine angle and position
        // -----------------------------------
        $length	= strlen($word);
        $angle	= ($length >= 6) ? mt_rand(-($length-6), ($length-6)) : 0;
        $x_axis	= mt_rand(6, (360/$length)-16);
        $y_axis = ($angle >= 0) ? mt_rand($img_height, $img_width) : mt_rand(6, $img_height);

        // Create image
        // PHP.net recommends imagecreatetruecolor(), but it isn't always available
        $im = function_exists('imagecreatetruecolor')
            ? imagecreatetruecolor($img_width, $img_height)
            : imagecreate($img_width, $img_height);

        // -----------------------------------
        //  Assign colors
        // ----------------------------------

        is_array($colors) OR $colors = $defaults['colors'];

        foreach (array_keys($defaults['colors']) as $key)
        {
            // Check for a possible missing value
            is_array($colors[$key]) OR $colors[$key] = $defaults['colors'][$key];
            $colors[$key] = imagecolorallocate($im, $colors[$key][0], $colors[$key][1], $colors[$key][2]);
        }

        // Create the rectangle
        ImageFilledRectangle($im, 0, 0, $img_width, $img_height, $colors['background']);

        // -----------------------------------
        //  Create the spiral pattern
        // -----------------------------------
        $theta		= 1;
        $thetac		= 7;
        $radius		= 16;
        $circles	= 20;
        $points		= 32;

        for ($i = 0, $cp = ($circles * $points) - 1; $i < $cp; $i++)
        {
            $theta += $thetac;
            $rad = $radius * ($i / $points);
            $x = ($rad * cos($theta)) + $x_axis;
            $y = ($rad * sin($theta)) + $y_axis;
            $theta += $thetac;
            $rad1 = $radius * (($i + 1) / $points);
            $x1 = ($rad1 * cos($theta)) + $x_axis;
            $y1 = ($rad1 * sin($theta)) + $y_axis;
            imageline($im, $x, $y, $x1, $y1, $colors['grid']);
            $theta -= $thetac;
        }

        // -----------------------------------
        //  Write the text
        // -----------------------------------

        $use_font = ($font_path !== '' && file_exists($font_path) && function_exists('imagettftext'));
        if ($use_font === FALSE)
        {
            ($font_size > 5) && $font_size = 5;
            $x = mt_rand(0, $img_width / ($length / 3));
            $y = 0;
        }
        else
        {
            ($font_size > 30) && $font_size = 30;
            $x = mt_rand(0, $img_width / ($length));
            $y = $font_size + 2;
        }

        for ($i = 0; $i < $length; $i++)
        {
            if ($use_font === FALSE)
            {
                $y = mt_rand(0 , $img_height / 2);
                imagestring($im, $font_size, $x, $y, $word[$i], $colors['text']);
                $x += ($font_size * 2);
            }
            else
            {
                $y = mt_rand($img_height / 2, $img_height/1.5);
                imagettftext($im, $font_size, $angle, $x, $y, $colors['text'], $font_path, $word[$i]);
                $x += $font_size;
            }
        }

        // Create the border
        imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $colors['border']);

        // -----------------------------------
        //  Generate the image
        // -----------------------------------
        $img_url = rtrim($img_url, '/').'/';

        if (function_exists('imagejpeg'))
        {
            $img_filename = $now.'.jpg';
            imagejpeg($im, $img_path.$img_filename);
        }
        elseif (function_exists('imagepng'))
        {
            $img_filename = $now.'.png';
            imagepng($im, $img_path.$img_filename);
        }
        else
        {
            return FALSE;
        }

        $img = '<img '.($img_id === '' ? '' : 'id="'.$img_id.'"').' src="'.$img_url.$img_filename.'" style="width: '.$img_width.'; height: '.$img_height .'; border: 0;" alt=" " />';
        ImageDestroy($im);

        return array('word' => $word, 'result' => $result, 'time' => $now, 'image' => $img, 'filename' => $img_filename);
    }
}
