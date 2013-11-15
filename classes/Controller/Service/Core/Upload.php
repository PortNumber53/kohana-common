<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Date: 11/10/13
 * Time: 2:57 AM
 * Something meaningful about this file
 *
 */

class Controller_Service_Core_Upload extends Controller_Service_Core_Service
{


	public function action_ajax_receive()
	{
		// If you want to ignore the uploaded files,
		// set $demo_mode to true;
		$demo_mode = FALSE;
		$upload_dir = DATAPATH . 'upload' . DIRECTORY_SEPARATOR;
		$allowed_ext = array('jpg','jpeg','png','gif');

		if (strtolower($_SERVER['REQUEST_METHOD']) != 'post')
		{
			$this->output['status'] = 'Error! Wrong HTTP method!';
			return;
		}

		if (array_key_exists('pic',$_FILES) && $_FILES['pic']['error'] == 0 )
		{
			$pic = $_FILES['pic'];
			if (!in_array($this->get_extension($pic['name']),$allowed_ext))
			{
				$this->output['status'] = 'Only '.implode(',',$allowed_ext).' files are allowed!';
				return;
			}
			if ($demo_mode)
			{
				// File uploads are ignored. We only log them.
				$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], $pic['name']));
				file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
				$this->output['status'] = 'Uploads are ignored in demo mode.';
				return;
			}

			// Move the uploaded file from the temporary
			// directory to the uploads folder:

			if(move_uploaded_file($pic['tmp_name'], $upload_dir.$pic['name'])){
				$this->output['status'] = 'File was uploaded successfuly!';
				$this->output['dismiss_timer'] = 1;
				$this->output['filename'] = $pic['name'];
				return;
			}
		}
		$this->output['status'] = 'Something went wrong with your upload!';
		// Helper functions
	}

	function exit_status($str){
		echo json_encode(array('status'=>$str));
		exit;
	}

	function get_extension($file_name){
		$ext = explode('.', $file_name);
		$ext = array_pop($ext);
		return strtolower($ext);
	}

}