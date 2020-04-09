<?php
//获取文件扩展名
if(!function_exists('GetFileExtension')){
	function GetFileExtension($file){
		$pathinfo=pathinfo($file);
		$extension=isset($pathinfo['extension'])?$pathinfo['extension']:'';
		$extension=strtolower($extension);
		return $extension;
	}
}

if ( ! function_exists('get_mimes'))
{
	/**
	 * Returns the MIME types array from config/mimes.php
	 *
	 * @return	array
	 */
	function &get_mimes()
	{
		static $_mimes;
		$path=dirname(__FILE__).DIRECTORY_SEPARATOR . 'mimes.php';
		
		if (empty($_mimes))
		{
			$_mimes = file_exists($path)
			? include($path)
			: array();
			
			
		}
		
		return $_mimes;
	}
}

if (!function_exists('get_mime_by_extension')) {
	/**
	 * Get Mime by Extension
	 *
	 * Translates a file extension into a mime type based on config/mimes.php.
	 * Returns FALSE if it can't determine the type, or open the mime config file
	 *
	 * Note: this is NOT an accurate way of determining file mime types, and is here strictly as a convenience
	 * It should NOT be trusted, and should certainly NOT be used for security
	 *
	 * @param	string	$filename	File name
	 * @return	string
	 */
	function get_mime_by_extension($filename)
	{
		static $mimes;
		
		if ( ! is_array($mimes))
		{
			$mimes = get_mimes();
			
			if (empty($mimes))
			{
				return FALSE;
			}
		}
// 		echo var_export($mimes);
		$extension = strtolower(substr(strrchr($filename, '.'), 1));
		
		if (isset($mimes[$extension]))
		{
			return is_array($mimes[$extension])
			? current($mimes[$extension]) // Multiple mime types, just give the first one
			: $mimes[$extension];
		}
		
		return FALSE;
	}
}