<?php

	/*
		SF-CaptchaClass v
		Copyright (c) 2006, Alexander M. Korn
		
		based on
		Jax Captcha Class v1.o1
		Copyright (c) 2005, Andreas John aka Jack (tR)
		
		This program and it's moduls are Open Source in terms of General Public License (GPL) v2.0
	
		class.captcha.php 		(captcha class module)
		
		Last modification: 2006-09-24
	*/
	
	class captcha
	{
		var $session_key = null;
		var $temp_dir    = null;
		var $width       = 110;
		var $height      = 40;
		var $jpg_quality = 97;
		var $name_addon  = "";
		var $style	 		 = '';
		/**
		 * Constructor - Initializes Captcha class!
		 */
		function captcha() {}
		
		/**
		 * Set needed vals for image generation
		 */
		function setCommons($session_key, $temp_dir, $nadd ,$width ,$height,$style) {
			$this->session_key = $session_key;
			$this->temp_dir    = $temp_dir;
			$this->width			 = $width;
			$this->height			 = $height;
			$this->style			 = $style;
			$this->name_addon = $nadd;
		}
		

		/**
		 * Generates Image file for captcha
		 *
		 * @param string $location
		 * @param string $char_seq
		 * @return unknown
		 */
		function _generate_image( $location, $char_seq )
		{
			global $cfg_cms;
			$num_chars = strlen($char_seq);
			
			$img = imagecreatetruecolor( $this->width, $this->height );
			imagealphablending($img, 1);
			//imagecolortransparent( $img );
			if (function_exists('imageantialias')) {
				imageantialias( $img,1 );
			}
			
			switch ($this->style)
			{
				case "1":

				$ttfont=$cfg_cms["cms_path"].'tpl/fonts/courierbold.ttf';

				$fontsize = $this->height/1.7;
				
				$fx_strength=$fontsize;
				
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_height=-$ts[5];

				$start_y=$this->height+($ttfchar_height/4);
				
				// calculate width of the code + space
				for ($i=0;$i<=$num_chars;$i++){
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$chars_width = $chars_width+$ts[2];
				}
				
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_width=$ts[2];

				$chars_multiply=(($this->width-$chars_width)/($num_chars+1));

				$colorb = imagecolorallocate( $img,0, 0, 0);
				$colorw = imagecolorallocate( $img,255,255,255);

				// set each letter with random angle, size and color
				for ($h=0;$h<round($this->height/($ttfchar_height/2))+1;$h++){
					for ($i=0;$i<=20;$i++){
						$r = round( rand( 100, 200 ) );
						$color = imagecolorallocate( $img, $r, $r, $r);
						ImageTTFText( $img, $fontsize/2, $angle, $x-($chars_multiply/4),$start_y-(($ttfchar_height*$h)), $color, $ttfont, rand(0,1) );
						$ts=imagettfbbox($fontsize/2, 0, $ttfont, "0" );
						$current_char_width = $ts[2];
						$x= $current_char_width+$x+($chars_multiply/2);
					}
					$x=0;
				}
				$start_y=$ttfchar_height+(($this->height-$ttfchar_height)/2);
				// set each letter with random angle, size and color
				for ($i=0;$i<=$num_chars;$i++){
					$r = round( rand( 200, 255 ) );
					$color = imagecolorallocate( $img, $r, $r, $r);
					ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x-1,$start_y, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x+1,$start_y, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x,$start_y, $color, $ttfont, substr($char_seq,$i,1) );
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$current_char_width = $ts[2];
					$x= $current_char_width+$x+$chars_multiply;
				}

				break;
				
				case "2":

				$ttfont=$cfg_cms["cms_path"].'tpl/fonts/pixelate.ttf';

				$fontsize = $this->height/1.7;
				
				$fx_strength=$fontsize;
				
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_height=-$ts[5];

				$start_y=$ttfchar_height+(($this->height-$ttfchar_height)/2);
				
				// calculate width of the code + space
				for ($i=0;$i<=$num_chars;$i++){
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$chars_width = $chars_width+$ts[2];
				}
				
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_width=$ts[2];

				$chars_multiply=(($this->width-$chars_width)/($num_chars+1));

				$colorb = imagecolorallocate( $img,0, 0, 0);
				$colorw = imagecolorallocate( $img,255,255,255);
				for ($n=$fx_strength;$n>0;$n--){
					$n_rnd=$fx_strength;
					for ($i=0;$i<=$num_chars;$i++){	
						$r = round( rand(100,200 ) );

						$color = imagecolorallocate( $img, $r, $r, $r);
					
						if ($n%2)
							$color_fx = imagecolorallocate( $img, $r-($n*$n_rnd),$r-($n*$n_rnd), $r-($n*$n_rnd));
						else
							$color_fx = imagecolorallocate( $img,100-$r-($n*$n_rnd),100-$r-($n*$n_rnd), 100-$r-($n*$n_rnd));
					
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x-($n),$start_y,$color_fx, $ttfont, substr($char_seq,$i,1) );				
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x+($n),$start_y,$color_fx, $ttfont, substr($char_seq,$i,1) );				
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x,$start_y-($n),$color_fx, $ttfont, substr($char_seq,$i,1) );				
						ImageTTFText( $img, $fontsize,0, $chars_multiply+$x,$start_y+($n),$color_fx, $ttfont, substr($char_seq,$i,1) );
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x-($n),$start_y-($n),$color_fx, $ttfont, substr($char_seq,$i,1) );				
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x+($n),$start_y+($n),$color_fx, $ttfont, substr($char_seq,$i,1) );	
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x-($n),$start_y+($n),$color_fx, $ttfont, substr($char_seq,$i,1) );				
						ImageTTFText( $img, $fontsize, 0, $chars_multiply+$x+($n),$start_y-($n),$color_fx, $ttfont, substr($char_seq,$i,1) );		
						$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
						$current_char_width = $ts[2];
						$x= $current_char_width+$x+$chars_multiply;	
					}
					$x=0;
				}

				// set each letter with random angle, size and color
				for ($i=0;$i<=$num_chars;$i++){
					$r = round( rand( 200, 255 ) );
					$color = imagecolorallocate( $img, $r, $r, $r);
					ImageTTFText( $img, $fontsize, $angle, $chars_multiply+$x,$start_y, $color, $ttfont, substr($char_seq,$i,1) );
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$current_char_width = $ts[2];
					$x= $current_char_width+$x+$chars_multiply;
				}
				break;
			
				default:

				$ttfont=$cfg_cms["cms_path"].'tpl/fonts/timesnewromanbold.ttf';
				$fontsize = $this->height/1.7;
				$fx_strength=$fontsize;
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_height=-$ts[5];
				$start_y=$ttfchar_height+(($this->height-$ttfchar_height)/2);
				
				// calculate width of the code + space
				for ($i=0;$i<=$num_chars;$i++){
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$chars_width = $chars_width+$ts[2];
				}
				
				$ts=imagettfbbox($fontsize, 0, $ttfont, "M" );
				$ttfchar_width=$ts[2];

				$chars_multiply=(($this->width-$chars_width)/($num_chars+1));

				$colorb = imagecolorallocate( $img,0, 0, 0);
				$colorw = imagecolorallocate( $img,255,255,255);
				$c=0;
				$x=0;
					// set each letter with random angle, size and color
				for ($i=0;$i<=40;$i++){
					$c++;
					$r = round( rand( 60, 180 ) );
					$color = imagecolorallocate( $img, $r, $r, $r);
					$presign = round( rand( 0, 1 ) );
					$angle = round( rand( 2, 15 ) );
					$fontsize_rnd=$fontsize- rand( -10,10 );
					if ($presign==true) $angle = -1*$angle;
					$char=chr(round(rand(33,122)));
					ImageTTFText( $img, $fontsize_rnd, $angle, $chars_multiply+$x+rand(-$fontsize_rnd,$fontsize_rnd),$start_y+rand(-$fontsize_rnd,$fontsize_rnd), $color, $ttfont, $char);
					
					$ts=imagettfbbox($fontsize_rnd, 0, $ttfont, $char);
					$current_char_width = $ts[2];
					$x= $current_char_width+$x+$chars_multiply;
				
					if ($c>4) {
						$c=0;
						$x=0;
					}	
				}


				$colorb = imagecolorallocate( $img,0, 0, 0);
				$colorw = imagecolorallocate( $img,255,255,255);
				$x=0;
					// set each letter with random angle, size and color
				for ($i=0;$i<=$num_chars;$i++){
					$r = round( rand( 200, 255 ) );
					$color = imagecolorallocate( $img, $r, $r, $r);
					$presign = round( rand( 0, 1 ) );
					$angle = round( rand( 2, 15 ) );
					$fontsize_rnd=$fontsize- rand( -6,3 );
					if ($presign==true) $angle = -1*$angle;
					
					ImageTTFText( $img, $fontsize_rnd, $angle, $chars_multiply+$x+1,$start_y, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img,$fontsize_rnd, $angle, $chars_multiply+$x-1,$start_y, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img,$fontsize_rnd, $angle, $chars_multiply+$x,$start_y+1, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img, $fontsize_rnd, $angle, $chars_multiply+$x,$start_y-1, $colorb, $ttfont, substr($char_seq,$i,1) );
					ImageTTFText( $img,$fontsize_rnd, $angle, $chars_multiply+$x,$start_y, $color, $ttfont, substr($char_seq,$i,1) );
					
					$ts=imagettfbbox($fontsize, 0, $ttfont, substr($char_seq,$i,1) );
					$current_char_width = $ts[2];
					$x= $current_char_width+$x+$chars_multiply;
				}
				break;

			}
			// create image file
			imagejpeg( $img, $location, $this->jpg_quality );
			imagedestroy( $img );

			return true;
		}
		
		
		/**
		 * Returns name of the new generated captcha image file
		 *
		 * @param unknown_type $num_chars
		 * @return unknown
		 */
		function get_pic( $num_chars=8 )
		{
			// define characters of which the captcha can consist
			$alphabet = array( 
				'A','B','C','D','E','F','G','H','J','K','L','M',
				'N','P','Q','R','S','T','U','V','W','X','Y','Z',
				'2','3','4','5','6','7','8','9' );
				
			$max = sizeof( $alphabet );
			
			// generate random string
			$captcha_str = '';
			for ($i=1;$i<=$num_chars;$i++) // from 1..$num_chars
			{
				// choose randomly a character from alphabet and append it to string
				$chosen = rand( 1, $max );
				$captcha_str .= $alphabet[$chosen-1];
			}
			
			// generate a picture file that displays the random string
			if ( $this->_generate_image( $this->temp_dir.'/'.$this->name_addon.'cap_'.md5( strtolower( $captcha_str )).'.jpg' , $captcha_str ) )
			{
				$fh = fopen( $this->temp_dir.'/'.$this->name_addon.'cap_'.$this->session_key.'.txt', "w" );
				fputs( $fh, md5( strtolower( $captcha_str ) ) );
				fclose($fh);
				return( md5( strtolower( $captcha_str ) ) );
			}
			else 
			{
				return false;
			}
		}
		
		/**
		 * check hash of password against hash of searched characters
		 *
		 * @param string $char_seq
		 * @return boolean
		 */
		function verify($session, $char_seq, $name_addon, $temp_dir, $delete_files_after_test = true )
		{	
	      if (! is_file($temp_dir.'/'.$name_addon.'cap_'.$session.'.txt') ) {
	        return false;
	      }
			$fh = fopen($temp_dir.'/'.$name_addon.'cap_'.$session.'.txt', "r" );
			$hash = fgets( $fh );
			fclose($fh);
			if ($delete_files_after_test) {
				@unlink($temp_dir.'/'.$name_addon.'cap_'.$session.'.txt');
				@unlink($temp_dir.'/'.$name_addon.'cap_'.$hash.'.jpg');
			}
			
			if (md5(strtolower($char_seq)) == $hash) {
				return true;
			} else { 
				return false;
			}

		}		
	}


?>
