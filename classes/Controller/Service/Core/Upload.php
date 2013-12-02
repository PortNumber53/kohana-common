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
				$line = implode('		', array( date('r'), $_SERVER['REMOTE_ADDR'], $pic['size'], URL::title($pic['name'])));
				file_put_contents('log.txt', $line.PHP_EOL, FILE_APPEND);
				$this->output['status'] = 'Uploads are ignored in demo mode.';
				return;
			}

			// Move the uploaded file from the temporary
			// directory to the uploads folder:

			$routes = Route::all();
			$matched = FALSE;
			foreach ($routes as $route)
			{
				$params = $route->matches(Request::factory(URL::site($this->request->headers('Referer'), FALSE)));
				if ($params)
				{
					$matched = $params;break;
				}
			}
			//Extra subfolders from referrer
			if ($matched['controller'] == 'Account')
			{
				$matched['id'] = Account::logged_in()['object_id'];
			}

			$group = str_pad($matched['id'] % 1000, 3, '0', STR_PAD_LEFT);
			$extra = strtolower($matched['controller'] . DIRECTORY_SEPARATOR . $group . DIRECTORY_SEPARATOR . $matched['id'] . DIRECTORY_SEPARATOR);
			if ( ! is_dir($upload_dir . $extra))
			{
				mkdir($upload_dir . $extra, 0755, TRUE);
			}
			$this->output['subfolder'] = $extra;
			//$this->output['route'] = $matched;
			//$this->output['routes'] = $routes;

			$target_file = $upload_dir . $extra . $pic['name'];
			if (move_uploaded_file($pic['tmp_name'], $target_file))
			{
				$path_parts = pathinfo($target_file);

				$finfo = finfo_open();
				$mime = finfo_file($finfo, $target_file, FILEINFO_MIME);
				finfo_close($finfo);
				$mimetype = explode(';', $mime);
				switch ($mimetype[0])
				{
					case "image/gif":
					case "image/jpg":
					case "image/jpeg":
					case "image/png":
						$image = new Imagick($target_file);
						$good_name = $extra . URLify::filter($path_parts['filename']) . '.' . $path_parts['extension'];

						$format = $image->getImageFormat();
						if ($format == 'GIF')
						{
							copy($target_file, $upload_dir . $good_name);
							/*
							list($width, $height) = getimagesize($target_file);
							var_dump($width);var_dump($height);
							$geo = $image->getImageGeometry();
							$sizex = $geo['width'];
							$sizey = $geo['height'];
							var_dump($geo);
							$image = $image->coalesceImages();
							do {
								$image->resizeImage($width, $height, Imagick::FILTER_BOX, 1);
							} while ($image->nextImage());
							$image = $image->deconstructImages();

							$image->writeImages($upload_dir . $good_name, TRUE);
							*/
						}
						else
						{
							//$image->setformat($path_parts['extension']);
							$image->writeimage($upload_dir . $good_name);
						}
						if ("$target_file" != "$upload_dir$good_name")
						{
							$this->output['target_file'] = "$target_file";
							$this->output['good_name'] = "$upload_dir$good_name";
							$this->output['different'] = 'UNLINK';
							unlink($target_file);
						}
						$this->output['status'] = 'File was uploaded successfuly!';
						$this->output['dismiss_timer'] = 1;
						$this->output['filename'] = $good_name;
						$this->output['referrer'] = URL::site($this->request->headers('Referer'), FALSE);
						break;
					default:
				}

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