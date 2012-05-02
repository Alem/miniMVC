<?php


class PublicHtml extends Template{

	public function __construct( $name ){
		parent::__construct($name);
		$this -> template_path = GIMIMVC_ROOT . 'system/gimimvc/templates/skeleton/';
		$this -> destination_path =  GIMIMVC_ROOT . 'applications/' . $this -> name . '/';
		$this -> full_listing = $this -> recursiveList( 'public_html', $this -> template_path );
	}

	public function generate(){
		foreach ( $this -> full_listing['dirs'] as  $dir_name ){
			if ( !file_exists(  $this -> destination_path . '/' . $dir_name ) ){
				echo 'Creating : ' . $this -> destination_path . '/' . $dir_name . "\n";
				mkdir ( $this -> destination_path . '/' . $dir_name , 0755, true );
			}
		}

		foreach( $this -> full_listing['files'] as $file_path )
			copy( $this -> template_path . $file_path, $this -> destination_path . $file_path );

	}

	public function undo(){
		foreach( $this -> full_listing['files'] as $file_path ){
			unlink( $this -> destination_path . $file_path );
		}

		foreach ( array_reverse( $this -> full_listing['dirs'] ) as  $dir_name ){
			if ( file_exists(  $this -> destination_path . '/' . $dir_name ) ){
				echo 'Removing : ' . $this -> destination_path . '/' . $dir_name . "\n";
				rmdir ( $this -> destination_path . '/' . $dir_name );
			}
		}
	}


	public function recursiveList( $rel_path, $root_path, &$full_listing = array() ){
		echo 'Loading :' . $root_path . $rel_path . "\n";
		$listing = scandir( $root_path . $rel_path );
		$full_listing['dirs'][] = $rel_path;
		foreach ( $listing as $file ){
			if ( ($file != '..') && ($file != '.') ){
				if( is_dir( $root_path . $rel_path . '/' . $file ) ){
					$full_listing[ 'dirs' ][] = $rel_path . '/' . $file;
					$this -> recursiveList( $rel_path . '/' . $file , $root_path, &$full_listing );
				}else
					$full_listing[ 'files' ][] = $rel_path . '/' . $file;
			}
		}
		return $full_listing;
	}
}


?>
