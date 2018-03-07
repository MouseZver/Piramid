<?php

spl_autoload_register ( function ( $name )
{
	$replaces = [ 
		'\\' => DIRECTORY_SEPARATOR, 
		
		# Aero Piramid
		'Aero\\' => '../src/Piramid/' 
	];
	
	include strtr ( $name, $replaces ) . '.php';
} );