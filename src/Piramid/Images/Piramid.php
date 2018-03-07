<?php

namespace Aero\Images;

final class Piramid
{
	protected $border = [
		'width' => 13,
		'height' => 13
	];
	protected $between = 3;
	protected $thickness = 27;
	protected $size = 230;
	protected $font = __DIR__ . '/fonds/arial.ttf';
	protected $fontsize = 14;
	
	/*
		-
	*/
	public function __construct ( array $array )
	{
		$this -> items = $array;
		
		$this -> count = count ( $array );
	}
	
	
	public function __call( $name, $args )
	{
		if ( $name === 'up' )
		{
			sort ( $this -> items );
			
			$this -> percent = ( $this -> size - $this -> border['width'] * 2 ) / end ( $this -> items );
		}
		elseif ( $name === 'down' )
		{
			rsort ( $this -> items );
			
			$this -> percent = ( $this -> size - $this -> border['width'] * 2 ) / $this -> items[0];
		}
		
		return $this;
	}
	
	/*
		- 380 / 2 - wFont
		- ( $this -> size - $this -> border['width'] ) / 2
	*/
	public function execute()
	{
		$im = imagecreatetruecolor ( $this -> size, ( $this -> count * $this -> thickness + ( $this -> count - 1 ) * $this -> between + $this -> border['height'] * 2 ) );

		imagefill ( $im, 0, 0, imagecolorallocate ( $im, 255, 255, 255 ) );
		
		foreach ( $this -> items AS $item )
		{
			$div = imagecreatetruecolor ( ( $x = ceil ( $this -> percent * $item ) ?: 1 ), $this -> thickness );
			
			imagefill ( $div, 0, 0, imagecolorallocate ( $div, rand ( 0, 255 ), rand ( 0, 255 ), rand ( 0, 255 ) ) );
			
			$center = ceil ( ( $this -> size - $x ) / 2 );
			
			ImageCopyResampled ( $im, $div, $center, $this -> border['height'], 0, 0, $x, $this -> thickness, $x, $this -> thickness );
			
			$box = imagettfbbox ( $this -> fontsize, 0, $this -> font, $item );
			
			$text = [ 'y' => ceil ( $this -> border['height'] + ( $this -> thickness + $this -> fontsize ) / 2 ) ];
			
			if ( $x > $box[2] + 2 )
			{
				$text['x'] = ceil ( ( $this -> size - $box[2] ) / 2 );
				
				$text['color'] = [ 255, 255, 255 ];
			}
			else
			{
				$text['x'] = $center + $x + 2;
				
				$text['color'] = [ 0, 0, 0 ];
			}
			
			imagettftext ( $im, $this -> fontsize, 0, $text['x'], $text['y'], imagecolorallocate ( $im, ...$text['color'] ), $this -> font, $item );
			
			$this -> border['height'] += ( $this -> thickness + $this -> between );
		}
		
		header ( 'Content-Type: image/png' );
		
		imagepng ( $im );
		
		imagedestroy ( $im );
	}
}