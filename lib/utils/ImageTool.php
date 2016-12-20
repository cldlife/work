<?php
/**
 * Image Class
 * Methods and functions for handling thumbnail generation
 */
class ImageTool {
	/**
	 * Type of incoming data
	 *
	 * @var string Type
	 */
	public $inType = 'file';
	/**
	 * Type of outgoing data
	 *
	 * @var string Type
	 */
	public $outType = 'file';
	/**
	 * Save file as...
	 *
	 * @var string File name
	 */
	public $outFileName = '';
	/**
	 * Save file in...
	 *
	 * @var string Directory
	 */
	public $outFileDir = '';
	/**
	 * In file directory
	 *
	 * @var string Directory
	 */
	public $inFileDir = '.';
	/**
	 * Name of input file name
	 *
	 * @var string File name
	 */
	public $inFileName = '';
	/**
	 * Complete file name with path
	 *
	 * @var string
	 */
	public $inFileComplete = '';
	/**
	 * Max. thumbnail width
	 *
	 * @var integer Pixels
	 */
	public $desiredWidth = '';
	
	/**
	 * Max. thumbnail height
	 *
	 * @var integer Pixels
	 */
	public $desiredHeight = '';
	/**
	 * GD version
	 *
	 * @var integer	GD version
	 */
	private $gdVersion = 2;
	/**
	 * Image type (PNG, JPG, GIF)
	 *
	 * @var string Image type
	 */
	public $imageType = '';
	/**
	 * File extension
	 *
	 * @var string
	 */
	public $fileExtension = '';
	/*-------------------------------------------------------------------------*/
	// CONSTRUCTOR
	/*-------------------------------------------------------------------------*/
	
	public function __construct() {
		//-----------------------------------
	// Full path?
	//-----------------------------------
	}
	
	/*-------------------------------------------------------------------------*/
	// Clean paths
	/*-------------------------------------------------------------------------*/
	
	/**
	 * Cleans up paths, generates var $in_file_complete
	 *
	 * @return void
	 */
	private function cleanPaths() {
		$this->inFileDir = preg_replace ( "#/$#", "", $this->inFileDir );
		$this->outFileDir = preg_replace ( "#/$#", "", $this->outFileDir );
		
		if ($this->inFileDir and $this->inFileName) {
			$this->inFileComplete = $this->inFileDir . '/' . $this->inFileName;
		} else {
			$this->inFileComplete = $this->inFileName;
		}
		
		if (! $this->outFileDir) {
			$this->outFileDir = $this->inFileDir;
		}
	}
	
	/*-------------------------------------------------------------------------*/
	// GENERATE THUMBNAIL
	/*-------------------------------------------------------------------------*/
	
	/**
	 * Generates thumnbail
	 *
	 * @return array	[ thumb_width, thumb_height, thumb_location ]
	 */
	public function generateThumbnail($type) {
		$return = array ();
		$image = "";
		$thumb = "";
		
		//-----------------------------------
		// Set up paths
		//-----------------------------------
		

		$this->cleanPaths ();
		
		$remap = array (1 => 'GIF', 2 => 'JPG', 3 => 'PNG', 4 => 'SWF', 5 => 'PSD', 6 => 'BMP' );
		
		if ($this->desiredWidth and $this->desiredHeight) {
			//----------------------------------------------------
			// Tom Thumb!
			//----------------------------------------------------
			

			$img_size = array ();
			
			if ($this->inType == 'file') {
				// Disable error reporting, to prevent PHP warnings
				$ER = error_reporting ( 0 );
				if (! is_file ( $this->inFileComplete ))
					throw new Exception ( 'image.file_not_found' . $this->inFileComplete );
					// Fetch the image size and mime type
				$img_size = getimagesize ( $this->inFileComplete );
				// Turn on error reporting again
				error_reporting ( $ER );
				
				// Make sure that the image is readable and valid
				if (! is_array ( $img_size ) or count ( $img_size ) < 3)
					throw new Exception ( 'image.file_unreadable' . $this->inFileComplete );
			
			}
			
			if ($img_size [0] < 1 and $img_size [1] < 1) {
				$img_size = array ();
				$img_size [0] = $this->desiredWidth;
				$img_size [1] = $this->desiredHeight;
				
				$return ['thumb_width'] = $this->desiredWidth;
				$return ['thumb_height'] = $this->desiredHeight;
				
				if ($this->outType == 'file') {
					$return ['thumb_location'] = $this->inFileName;
					return $return;
				} else {
					//----------------------------------------------------
					// Show image
					//----------------------------------------------------
					

					$this->showNonGd ();
				}
			}
			
			//----------------------------------------------------
			// Do we need to scale?
			//----------------------------------------------------
			

			if (($img_size [0] > $this->desiredWidth) or ($img_size [1] > $this->desiredHeight)) {
							
				$im = $this->scaleImage ( array ('max_width' => $this->desiredWidth, 'max_height' => $this->desiredHeight, 'cur_width' => $img_size [0], 'cur_height' => $img_size [1] ) );
				$return ['thumb_width'] = $im ['img_width'];
				$return ['thumb_height'] = $im ['img_height'];
				$return ['original_width'] = $img_size [0];
				$return ['original_height'] = $img_size [1];
				
				//-----------------------------------------------
				// May as well scale properly.
				//-----------------------------------------------
				

				if ($im ['img_width']) {
					$this->desiredWidth = $im ['img_width'];
				}
				
				if ($im ['img_height']) {
					$this->desiredHeight = $im ['img_height'];
				}
				
				//-----------------------------------------------
				// GD functions available?
				//-----------------------------------------------
	
				if ($remap [$img_size [2]] == 'GIF') {
					if (function_exists ( 'imagecreatefromgif' )) {
						if ($image = @imagecreatefromgif ( $this->inFileComplete )) {
							$this->imageType = 'gif';
						} else {
							if ($this->outType == 'file') {
								$return ['thumb_width'] = $this->desiredWidth;
								$return ['thumb_height'] = $this->desiredHeight;
								$return ['thumb_location'] = $this->inFileName;
								$return ['original_width'] = $img_size [0];
								$return ['original_height'] = $img_size [1];
							} else {
								//-----------------------------------------------
								// Show Image..
								//-----------------------------------------------
								

								$this->showNongd ();
							
							}
						}
					}
				} else if ($remap [$img_size [2]] == 'PNG') {
					if (function_exists ( 'imagecreatefrompng' )) {
						if ($image = @imagecreatefrompng ( $this->inFileComplete )) {
							$this->imageType = 'png';
						} else {
							if ($this->outType == 'file') {
								$return ['thumb_width'] = $this->desiredWidth;
								$return ['thumb_height'] = $this->desiredHeight;
								$return ['thumb_location'] = $this->inFileName;
								$return ['original_width'] = $img_size [0];
								$return ['original_height'] = $img_size [1];
							} else {
								//-----------------------------------------------
								// Show Image..
								//-----------------------------------------------
								

								$this->showNonGd ();
							
							}
						}
					}
				} else if ($remap [$img_size [2]] == 'JPG') {
				
					if (function_exists ( 'imagecreatefromjpeg' )) {
						if ($image = @imagecreatefromjpeg ( $this->inFileComplete )) {
							$this->imageType = 'jpg';
						} else {
							if ($this->outType == 'file') {
								$return ['thumb_width'] = $this->desiredWidth;
								$return ['thumb_height'] = $this->desiredHeight;
								$return ['thumb_location'] = $this->inFileName;
								$return ['original_width'] = $img_size [0];
								$return ['original_height'] = $img_size [1];
							} else {
								//-----------------------------------------------
								// Show Image..
								//-----------------------------------------------
								

								$this->showNonGd ();
							
							}
						}
					}
				}else if($remap[$img_size [2]]=='BMP'){
					
					if ($image = $this->imagecreatefrombmp ( $this->inFileComplete )) {
							$this->imageType = 'jpg';
						} else {
							if ($this->outType == 'file') {
								$return ['thumb_width'] = $this->desiredWidth;
								$return ['thumb_height'] = $this->desiredHeight;
								$return ['thumb_location'] = $this->inFileName;
								$return ['original_width'] = $img_size [0];
								$return ['original_height'] = $img_size [1];
							} else {
								//-----------------------------------------------
								// Show Image..
								//-----------------------------------------------
								

								$this->showNonGd ();
							
							}
						}
				}
				
				//----------------------------------------------------
				// Did we get a return from imagecreatefrom?
				//----------------------------------------------------
				

				if ($image) {
					if ($this->gdVersion == 1) {
						$thumb = @imagecreate ( $im ['img_width'], $im ['img_height'] );
						@imagecopyresized ( $thumb, $image, 0, 0, 0, 0, $im ['img_width'], $im ['img_height'], $img_size [0], $img_size [1] );
					} else {
						
						$thumb = @imagecreatetruecolor ( $im ['img_width'], $im ['img_height'] );
						if ($this->imageType == 'png') {
							@imagealphablending ( $thumb, FALSE );
							@imagesavealpha ( $thumb, TRUE );
							
							$transparent = @imagecolorallocatealpha ( $thumb, 255, 255, 255, 127 );
							@imagefilledrectangle ( $thumb, 0, 0, $im ['img_width'], $im ['img_height'], $transparent );
						}
						
						@imagecopyresampled ( $thumb, $image, 0, 0, 0, 0, $im ['img_width'], $im ['img_height'], $img_size [0], $img_size [1] );
					}
					
					//-----------------------------------------------
					// Saving?
					//-----------------------------------------------
					

					if ($this->outType == 'file') {
						if (! $this->outFileName) {
							//-----------------------------------------------
							// Remove file extension...
							//-----------------------------------------------
							if ($type == 'm') {
								$this->outFileName = 'middle_' . preg_replace ( "/^(.*)\..+?$/", "\\1", $this->inFileName );
							} else
								$this->outFileName = 'thumb_' . preg_replace ( "/^(.*)\..+?$/", "\\1", $this->inFileName );
						}
						
			
						if (function_exists ( 'imagegif' ) and ($this->imageType == 'gif')) {

							$this->fileExtension = 'gif';
							@imagegif ( $thumb, $this->outFileDir . "/" . $this->outFileName . '.gif' );
							@chmod ( $this->outFileDir . "/" . $this->outFileName . '.gif', 0777 );
							@imagedestroy ( $thumb );
							@imagedestroy ( $image );							
							$return ['thumb_location'] = $this->outFileName . '.gif';
							return $return;
						} else if (function_exists ( 'imagepng' ) and ($this->imageType == 'png')) {
							$this->fileExtension = 'png';
							@imagepng ( $thumb, $this->outFileDir . "/" . $this->outFileName . '.png' );
							@chmod ( $this->outFileDir . "/" . $this->outFileName . '.png', 0777 );
							@imagedestroy ( $thumb );
							@imagedestroy ( $image );
							$return ['thumb_location'] = $this->outFileName . '.png';
							return $return;
						} else if (function_exists ( 'imagejpeg' )) {
				
//							$this->fileExtension = 'jpg';
							if($this->fileExtension != 'jpeg'){
							   $this->fileExtension = 'jpg';
							}
		
							@imagejpeg ( $thumb, $this->outFileDir . "/" . $this->outFileName . '.'.$this->fileExtension, 85);
							@chmod ( $this->outFileDir . "/" . $this->outFileName . '.'.$this->fileExtension, 0777 );
							@imagedestroy ( $thumb );
						 @imagedestroy ( $image );				
							$return ['thumb_location'] = $this->outFileName . '.'.$this->fileExtension;
							return $return;
						} else {
							//--------------------------------------
							// Can't save...
							//--------------------------------------
							$return ['thumb_width'] = $this->desiredWidth;
							$return ['thumb_height'] = $this->desiredHeight;
							$return ['thumb_location'] = $this->inFileName;
							$return ['original_width'] = $img_size [0];
							$return ['original_height'] = $img_size [1];
							
							return $return;
						}
					} else {
						//-----------------------------------------------
						// Show image
						//-----------------------------------------------
						

						$this->showImage ( $thumb, $this->imageType );
					
					}
				} else {
					//----------------------------------------------------
					// Could not GD, return..
					//----------------------------------------------------
					

					if ($this->outType == 'file') {
						$return ['thumb_width'] = $this->desiredWidth;
						$return ['thumb_height'] = $this->desiredHeight;
						$return ['thumb_location'] = $this->inFileName;
						$return ['original_width'] = $img_size [0];
						$return ['original_height'] = $img_size [1];
					} else {
						//-----------------------------------------------
						// Show Image..
						//-----------------------------------------------
						

						$this->showNonGd ();
					
					}
					
					return $return;
				}
			} //----------------------------------------------------
// No need to scale..
			//----------------------------------------------------
			else {
			  
				if ((($img_size [0] <=120 || $img_size [1] <=120) && !file_exists($this->outFileDir . "/" . 'thumb_' . $this->inFileName)) ) {
				  copy ( $this->outFileDir . "/" . $this->inFileName, $this->outFileDir . "/" . 'thumb_' . $this->inFileName );
				  copy ( $this->outFileDir . "/" . $this->inFileName, $this->outFileDir . "/" . 'middle_' . $this->inFileName );
				} elseif (($img_size [0] >= 120 && $img_size [0] < 600) || ($img_size [1] >= 120 && $img_size [1] < 600) && !file_exists($this->outFileDir . "/" . 'middle_' . $this->inFileName)) {
				  copy ( $this->outFileDir . "/" . $this->inFileName, $this->outFileDir . "/" . 'middle_' . $this->inFileName );
				}
				if ($this->outType == 'file') {
					//$return['thumb_location']  = $this->in_file_name;
					$return ['thumb_width'] = $img_size [0];
					$return ['thumb_height'] = $img_size [1];
					$return ['original_width'] = $img_size [0];
					$return ['original_height'] = $img_size [1];
					
					return $return;
				} else {
					//-----------------------------------------------
					// Show Image..
					//-----------------------------------------------
					

					$this->showNonGd ();
				
				}
			}
		}
	}
	
	/*-------------------------------------------------------------------------*/
	// Show GD image
	/*-------------------------------------------------------------------------*/
	
	/**
	 * Show GD image
	 *
	 * @param	string	Thumbnail data
	 * @param	string	Thumbnail type (gif, png, jpg)
	 * @return void
	 */
	private function showImage($thumb, $type) {
		flush ();
		
		if ($type == 'gif') {
			@header ( 'Content-type: image/gif' );
		} else if ($type == 'png') {
			@header ( 'Content-Type: image/png' );
		} else {
			@header ( 'Content-Type: image/jpeg' );
		}
		
		print $thumb;
		
		exit ();
	}
	
	/*-------------------------------------------------------------------------*/
	// Show non GD image
	/*-------------------------------------------------------------------------*/
	
	/**
	 * Show a NON GD image
	 *
	 * @return void
	 */
	private function showNonGd() {
		$file_extension = preg_replace ( ".*\.(\w+)$", "\\1", $this->inFileName );
		$file_extension = strtolower ( $file_extension );
//		$file_extension = $file_extension == 'jpeg' ? 'jpg' : $file_extension;
		
		if (strstr ( ' gif jpg png jpeg ', ' ' . $file_extension . ' ' )) {
			if ($data = @file_get_contents ( $this->inFileComplete )) {
				$this->showImage ( $data, $file_extension );
			}
		}
	}
	
	/*-------------------------------------------------------------------------*/
	// Return scaled down image
	/*-------------------------------------------------------------------------*/
	
	/**
	 * Scale an image
	 *
	 * @param	array	[ cur_height, cur_width, max_width, max_height ]
	 * @return	array	[ img_height, img_width ]
	 */
	private function scaleImage($arg) {
		// max_width, max_height, cur_width, cur_height
		

		$ret = array ('img_width' => $arg ['cur_width'], 'img_height' => $arg ['cur_height'] );
		
		if ($arg ['cur_width'] > $arg ['max_width']) {
			$ret ['img_width'] = $arg ['max_width'];
			$ret ['img_height'] = ceil ( ($arg ['cur_height'] * (($arg ['max_width'] * 100) / $arg ['cur_width'])) / 100 );
			$arg ['cur_height'] = $ret ['img_height'];
			$arg ['cur_width'] = $ret ['img_width'];
		}
		
		if ($arg ['cur_height'] > $arg ['max_height']) {
			$ret ['img_height'] = $arg ['max_height'];
			$ret ['img_width'] = ceil ( ($arg ['cur_width'] * (($arg ['max_height'] * 100) / $arg ['cur_height'])) / 100 );
		}
		
		return $ret;
	}
	
	private function imagecreatefrombmp($filename) {
		//Ouverture du fichier en mode binaire
		if (! $f1 = fopen ( $filename, "rb" ))
			return FALSE;
			
		//1 : Chargement des ent?tes FICHIER
		$FILE = unpack ( "vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread ( $f1, 14 ) );
		if ($FILE ['file_type'] != 19778)
			return FALSE;
			
		//2 : Chargement des ent?tes BMP
		$BMP = unpack ( 'Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' . '/Vcompression/Vsize_bitmap/Vhoriz_resolution' . '/Vvert_resolution/Vcolors_used/Vcolors_important', fread ( $f1, 40 ) );
		$BMP ['colors'] = pow ( 2, $BMP ['bits_per_pixel'] );
		if ($BMP ['size_bitmap'] == 0)
			$BMP ['size_bitmap'] = $FILE ['file_size'] - $FILE ['bitmap_offset'];
		$BMP ['bytes_per_pixel'] = $BMP ['bits_per_pixel'] / 8;
		$BMP ['bytes_per_pixel2'] = ceil ( $BMP ['bytes_per_pixel'] );
		$BMP ['decal'] = ($BMP ['width'] * $BMP ['bytes_per_pixel'] / 4);
		$BMP ['decal'] -= floor ( $BMP ['width'] * $BMP ['bytes_per_pixel'] / 4 );
		$BMP ['decal'] = 4 - (4 * $BMP ['decal']);
		if ($BMP ['decal'] == 4)
			$BMP ['decal'] = 0;
			
		//3 : Chargement des couleurs de la palette
		$PALETTE = array ();
		if ($BMP ['colors'] < 16777216) {
			$PALETTE = unpack ( 'V' . $BMP ['colors'], fread ( $f1, $BMP ['colors'] * 4 ) );
		}
		
		//4 : Cr?ation de l'image
		$IMG = fread ( $f1, $BMP ['size_bitmap'] );
		$VIDE = chr ( 0 );
		
		$res = imagecreatetruecolor ( $BMP ['width'], $BMP ['height'] );
		$P = 0;
		$Y = $BMP ['height'] - 1;
		while ( $Y >= 0 ) {
			$X = 0;
			while ( $X < $BMP ['width'] ) {
				if ($BMP ['bits_per_pixel'] == 24)
					$COLOR = unpack ( "V", substr ( $IMG, $P, 3 ) . $VIDE );
				elseif ($BMP ['bits_per_pixel'] == 16) {
					$COLOR = unpack ( "n", substr ( $IMG, $P, 2 ) );
					$COLOR [1] = $PALETTE [$COLOR [1] + 1];
				} elseif ($BMP ['bits_per_pixel'] == 8) {
					$COLOR = unpack ( "n", $VIDE . substr ( $IMG, $P, 1 ) );
					$COLOR [1] = $PALETTE [$COLOR [1] + 1];
				} elseif ($BMP ['bits_per_pixel'] == 4) {
					$COLOR = unpack ( "n", $VIDE . substr ( $IMG, floor ( $P ), 1 ) );
					if (($P * 2) % 2 == 0)
						$COLOR [1] = ($COLOR [1] >> 4);
					else
						$COLOR [1] = ($COLOR [1] & 0x0F);
					$COLOR [1] = $PALETTE [$COLOR [1] + 1];
				} elseif ($BMP ['bits_per_pixel'] == 1) {
					$COLOR = unpack ( "n", $VIDE . substr ( $IMG, floor ( $P ), 1 ) );
					if (($P * 8) % 8 == 0)
						$COLOR [1] = $COLOR [1] >> 7;
					elseif (($P * 8) % 8 == 1)
						$COLOR [1] = ($COLOR [1] & 0x40) >> 6;
					elseif (($P * 8) % 8 == 2)
						$COLOR [1] = ($COLOR [1] & 0x20) >> 5;
					elseif (($P * 8) % 8 == 3)
						$COLOR [1] = ($COLOR [1] & 0x10) >> 4;
					elseif (($P * 8) % 8 == 4)
						$COLOR [1] = ($COLOR [1] & 0x8) >> 3;
					elseif (($P * 8) % 8 == 5)
						$COLOR [1] = ($COLOR [1] & 0x4) >> 2;
					elseif (($P * 8) % 8 == 6)
						$COLOR [1] = ($COLOR [1] & 0x2) >> 1;
					elseif (($P * 8) % 8 == 7)
						$COLOR [1] = ($COLOR [1] & 0x1);
					$COLOR [1] = $PALETTE [$COLOR [1] + 1];
				} else
					return FALSE;
				imagesetpixel ( $res, $X, $Y, $COLOR [1] );
				$X ++;
				$P += $BMP ['bytes_per_pixel'];
			}
			$Y --;
			$P += $BMP ['decal'];
		}
		
		//Fermeture du fichier
		fclose ( $f1 );
		
		return $res;
	}

}

?>