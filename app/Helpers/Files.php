<?php
namespace App\Helpers;

class Files
{
    /*
    * The default value for recursive create dirs
    */
    private $recursiveDirectories = true;

    /*
    * Default value for chmod on create directory
    */
    private $defCHMOD = 0755;

    /*
     * Mine types of file
    */
    private $mineTypes = [
        'application/x-zip-compressed',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/gif',
        'image/jpeg',
        'image/jpeg',
        'audio/mpeg',
        'video/mp4',
        'application/pdf',
        'image/png',
        'application/zip',
        'application/et-stream',
        'image/x-icon',
        'image/icon',
        'image/svg+xml',
    ];
    /*
     * Types
    */
    private $types = [
        'image' => ['jpg', 'png', 'jpeg', 'gif', 'ico', 'svg'],
        'zip'   => ['zip', 'tar', '7zip', 'rar'],
        'docs'  => ['pdf', 'docs', 'docx'],
        'media' => ['mp4', 'mp3', 'wav', '3gp'],
    ];

    /*
     * resource
    */
    private $resource;
    /*
     * Mode of files
    */
    private $modes = [
        'readOnly'        => 'r',
        'readWrite'       => 'r+',
        'writeOnly'       => 'w',
        'writeMaster'     => 'w+',
        'writeAppend'     => 'a',
        'readWriteAppend' => 'a+',
    ];

    /**
     * Define the recursive create directories.
     *
     * @param $value recursive status true|false.
     *
     * @return current value
     */
    public function recursiveCreateDir($value = null)
    {
        if ($value === null) {
            return $this->recursiveDirectories;
        } else {
            $this->recursiveDirectories = $value;
        }
    }

    /**
     * Define the CHMOD for created dir.
     *
     * @param (string) $value CHMOD value default: 0755.
     *
     * @return current value
     */
    public function defaultCHMOD($value = null)
    {
        if ($value === null) {
            return $this->defCHMOD;
        } else {
            $this->defCHMOD = $value;
        }
    }

    /**
     * Add the mine type.
     *
     * @param (string) $type correct mine type.
     *
     * @return void
     */
    public function addMineTypes($type)
    {
        array_push($this->mineTypes, $type);
    }

    /**
     * Add the extemsio.
     *
     * @param (string) $type Correct type.
     * @param (strubg) $sub  Extensions
     *
     * @return void
     */
    public function addExt($type, $ext)
    {
        array_push($this->types[$type], $ext);
    }

    /**
     * Make the dir.
     *
     * @param (string) $name      Name of dir with path.
     * @param (string) $recursive Recursive mode create: null|true|false.
     * @param (string) $chmod     Directory permission on create: 0755
     *
     * @return bool
     */
    public function mkDir($name, $recursive = null, $chmod = null)
    {
        // test the recursive mode with default value
        $recursive = ($recursive === null) ? $this->recursiveDirectories : $recursive;
        // test the chmod with default value
        $chmod = ($chmod === null) ? $this->defCHMOD : $chmod;
        if (!is_dir($name)) {
            // this change to permit create dir in recursive mode
            return (mkdir($name, $chmod, $recursive)) ? true : false;
        }

        return false;
    }

    /**
     * Make the permission.
     *
     * @param (string) $source Name of file or directory with path.
     * @param (int) $pre       Valid premission
     *
     * @return bool
     */
    public function permission($source, $pre)
    {
        if (!is_dir($source)) {
            return (file_exists($source)) ? chmod($source, $pre) : false;
        }

        return false;
    }

    /**
     * Copy files.
     *
     * @param (string) $source name of file or directory with path.
     * @param (string) $target Target directory
     * @param (array)  $files  Files to be copy
     *
     * @return void
     */
    public function copyFiles($source, $target, $files)
    {
        $this->mkDir($target);
        foreach ($files as $file => $value) {
            if (file_exists($source.$value)) {
                copy($source.$value, $target.$value);
            }
        }
    }

    /**
     * Move files.
     *
     * @param (string) $source Name of file or directory with path.
     * @param (string) $target Target directory
     * @param (array)  $files  Files to be move
     *
     * @return void
     */
    public function moveFiles($source, $target, $files)
    {
        $this->mkDir($target);
        foreach ($files as $file => $value) {
            if (file_exists($source.$value)) {
                rename($source.$value, $target.$value);
            }
        }
    }

    /**
     * Delete files.
     *
     * @param (array) $file Name of file with path.
     *
     * @return void
     */
    public function deleteFiles($files)
    {
        foreach ($files as $file => $value) {
            if (file_exists($value)) {
                unlink($value);
            }
        }
    }

    /**
     * Copy dirs.
     *
     * @param (string) $source Directory with path.
     * @param (string) $target Target directory
     * @param (array)  $files  Dirs to be copy
     *
     * @return void
     */
    public function copyDir($source, $target, $dirs)
    {
        $this->mkDir($target);
        $serverOs = (new \App\Helpers\UserInfo)->oSystem();
        $command = ($serverOs === 'Windows') ? 'xcopy ' : 'cp -r ';
        foreach ($dirs as $dir => $value) {
            if (is_dir($source.$value)) {
                shell_exec($command.$source.$value.' '.$target.$value);
            }
        }
    }

    /**
     * Move dirs.
     *
     * @param (string) $source Directory with path.
     * @param (string) $target Target directory
     * @param (array)  $dir    Dir to be move
     *
     * @return void
     */
    public function moveDir($source, $target, $dirs)
    {
        $this->mkDir($target);
        $serverOs = (new \App\Helpers\UserInfo())->oSystem();
        $command = ($serverOs === 'Windows') ? 'move ' : 'mv ';
        foreach ($dirs as $dir => $value) {
            if (is_dir($source.$value)) {
                shell_exec($command.$source.$value.' '.$target.$value);
            }
        }
    }

    /**
     * Delete dirs.
     *
     * @param (string) $dir Directory with path.
     *
     * @return void
     */
    public function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new \InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    /**
     * Upload file.
     *
     * @param (string) $file    File to be uploaded.
     * @param (string) $target  Target where file should be upload
     * @param (string) $imgType Supported => image,media,docs,zip
     * @param (int)    $maxSize File size to be allowed
     *
     * @return void
     */
    public function fileUpload($file, $target, $imgType, $maxSize = 7992000000)
    {
        $exactName = basename($file['name']);
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $error = $file['error'];
        $type = $file['type'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = $this->rendomFileName(30);
        $fileNewName = $newName.'.'.$ext;
        $allowerd_ext = $this->types[$imgType];
        if (in_array($type, $this->mineTypes) === false) {
            return [
                'status' => false,
                'code'   => 'mineType',
            ];
        }
        if (in_array($ext, $allowerd_ext) === true) {
            if ($error === 0) {
                if ($fileSize <= $maxSize) {
                    $this->mkdir($target);
                    $fileRoot = $target.$fileNewName;
                    if (move_uploaded_file($fileTmp, $fileRoot)) {
                        return $fileNewName;
                    } else {
                        return [
                            'status' => false,
                            'code'   => 'somethingwrong',
                        ];
                    }
                } else {
                    return [
                        'status' => false,
                        'code'   => 'exceedlimit',
                    ];
                }
            } else {
                return [
                    'status' => false,
                    'code'   => $error,
                ];
            }
        } else {
            return [
                    'status' => false,
                    'code'   => 'extension',
            ];
        }
    }

    /**
     * Upload files.
     *
     * @param $files (array) files to be uploaded.
     *        $target target where file should be upload
     *        $imgType supported => image,media,docs,zip
     *        $maxSize file size to be allowed
     *
     * @return void
     */
    public function filesUpload($files, $target, $imgType, $count, $maxSize = 7992000000)
    {
        $status = [];
        for ($i = 0; $i < $count; $i++) {
            $exactName = basename($files['name'][$i]);
            $fileTmp = $files['tmp_name'][$i];
            $fileSize = $files['size'][$i];
            $error = $files['error'][$i];
            $type = $files['type'][$i];
            $ext = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
            $newName = $this->rendomFileName(30);
            $fileNewName = $newName.'.'.$ext;
            $allowerd_ext = $this->types[$imgType];
            if (in_array($type, $this->mineTypes) === false) {
                $status[$i] = [
                    'status' => false,
                    'code'   => 'mineType',
                ];
            }
            if (in_array($ext, $allowerd_ext) === true) {
                if ($error === 0) {
                    if ($fileSize <= $maxSize) {
                        $this->mkdir($target);
                        $fileRoot = $target.$fileNewName;
                        if (move_uploaded_file($fileTmp, $fileRoot)) {
                            $status[$i] = $fileNewName;
                        } else {
                            $status[$i] = [
                                'status' => false,
                                'code'   => 'somethingwrong',
                            ];
                        }
                    } else {
                        $status[$i] = [
                            'status' => false,
                            'code'   => 'exceedlimit',
                        ];
                    }
                } else {
                    $status[$i] = [
                        'status' => false,
                        'code'   => $error,
                    ];
                }
            } else {
                $status[$i] = [
                        'status' => false,
                        'code'   => $error,
                ];
            }
        }

        return $status;
    }

    public function Upload($target, $filename, $variable="name")
    {
      $original_filename = $_FILES[$variable]['name'];
      $ext = pathinfo($original_filename, PATHINFO_EXTENSION);
      $path_parts = pathinfo($original_filename, PATHINFO_FILENAME);
      $filename_without_ext = basename($original_filename, '.' . $ext);
      $newfilename = str_replace(' ', '-', strtolower($filename)) . '-' . microtime(true) . '-' . mt_rand(9999, 9999999). '.' . $ext;
      move_uploaded_file($_FILES[$variable]["tmp_name"], $target . $newfilename);
      return $newfilename;
    }

    /**
     * Generate Avatar Image
     * 
     * @param mixed $target folder
     * @param mixed $name of file
     * @param mixed $size=200
     * @param int $length (text length)
     * 
     * @return [type]
     */
    public function Avatar($target, $name, $size=200, $length = 8)
    {
        $content = file_get_contents('https://ui-avatars.com/api/?name=' . $name . '&background=00d97e&color=fff&size='.$size.'&font-size=0.13&length=' . $length . '&rounded=false&bold=false&uppercase=true');
        $uimg = uniqid(mt_rand()) . ".jpg";
        file_put_contents($target . $uimg, $content);
        return $uimg;
    }

    /**
     * Compress Images
     * 
     * @param mixed $source_image
     * @param mixed $tagrget folder
     * 
     * @return [type]
     */
    public function Compress($source_image, $target)
    {
        $image_info = getimagesize($source_image);
        if ($image_info['mime'] == 'image/jpeg') {
            $source_image = imagecreatefromjpeg($source_image);
            imagejpeg($source_image, $target, 80);
        } elseif ($image_info['mime'] == 'image/jpg') {
            $source_image = imagecreatefromjpeg($source_image);
            imagejpeg($source_image, $target, 80);
        } elseif ($image_info['mime'] == 'image/JPG') {
            $source_image = imagecreatefromjpeg($source_image);
            imagejpeg($source_image, $target, 80);
        }elseif ($image_info['mime'] == 'image/JPEG') {
            $source_image = imagecreatefromjpeg($source_image);
            imagejpeg($source_image, $target, 80);
        } elseif ($image_info['mime'] == 'image/gif') {
            $source_image = imagecreatefromgif($source_image);
            imagegif($source_image, $target, 80);
        } elseif ($image_info['mime'] == 'image/GIF') {
            $source_image = imagecreatefromgif($source_image);
            imagegif($source_image, $target, 80);
        } elseif ($image_info['mime'] == 'image/png') {
            $source_image = imagecreatefrompng($source_image);
            imagepng($source_image, $target, 6);
        } elseif ($image_info['mime'] == 'image/PNG') {
            $source_image = imagecreatefrompng($source_image);
            imagepng($source_image, $target, 6);
        }else{
            return [
                'status' => false,
                'code'   => 'file extension not supported',
            ];
        }
        return true;
    }

    public function byteSize($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function passUploadSize($bytes, $size)
    {
        if ($bytes >= 1073741824) {
            $bytes = ["size" => number_format($bytes / 1073741824, 2), "unit"=> 'GB'];
        } elseif ($bytes >= 1048576) {
            $bytes = ["size" => number_format($bytes / 1048576, 2) , "unit"=> 'MB'];
        } elseif ($bytes >= 1024) {
            $bytes = ["size" => number_format($bytes / 1024, 2) , "unit"=> 'KB'];
        } elseif ($bytes > 1) {
            $bytes = ["size" => $bytes , "unit"=> 'bytes'];
        } elseif ($bytes == 1) {
            $bytes = ["size" => $bytes , "unit"=> 'byte'];
        } else {
            $bytes = ["size" => 0, "unit"=> 'bytes'];
        }

        return $bytes;
    }

    /**
     * @param string $file
     * @param int $with
     * @param int $height
     * @return bool|null true/false if image has that exact size, null on error.
     */
    public function isImageSize($file, $width, $height)
    {
        $result = getimagesize($file);
        if (count($result) < 2) {
            return null;
        }

        list($file_width, $file_height) = $result;

        return ($file_width >= (int) $width) 
            && ($file_height >= (int) $height);
    }

    public function ImageResolution($file){
        $result = getimagesize($file);
        if (count($result) < 2) {
            return null;
        }

        list($file_width, $file_height) = $result;
        return ['width'=> $file_width, 'height'=> $file_height];
    }

    /**
     * Crop image to different sizes
     * 
     * @param mixed $source_file
     * @param mixed $target folder
     * @param mixed $filename to save file
     * @param int $tn_w
     * @param int $tn_h
     * @param int $quality
     * 
     * @return [type]
     */
    public function Crop($source_file, $target, $filename, $tn_w = 200, $tn_h = 200, $quality = 90)
    {
        $info = getimagesize($source_file);
        $imgtype = image_type_to_mime_type($info[2]);
        switch ($imgtype) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($source_file);
                break;
            case 'image/jpg':
                $source = imagecreatefromjpeg($source_file);
                break;
            case 'image/JPG':
                $source = imagecreatefromjpeg($source_file);
                break;
            case 'image/JPEG':
                $source = imagecreatefromjpeg($source_file);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($source_file);
                break;
            case 'image/GIF':
                $source = imagecreatefromgif($source_file);
                break;
            case 'image/png':
                $source = imagecreatefrompng($source_file);
                break;
            case 'image/PNG':
                $source = imagecreatefrompng($source_file);
                break;
            default:
                return [
                    'status' => false,
                    'code'   => 'This image is invalid',
                ];
        }
        $src_w = imagesx($source);
        $src_h = imagesy($source);
        $src_ratio = $src_w / $src_h;
        if ($tn_w / $tn_h > $src_ratio) {
            $new_h = $tn_w / $src_ratio;
            $new_w = $tn_w;
        } else {
            $new_w = $tn_h * $src_ratio;
            $new_h = $tn_h;
        }
        $x_mid = $new_w / 2;
        $y_mid = $new_h / 2;
        $newpic = imagecreatetruecolor(round($new_w), round($new_h));
        imagecopyresampled($newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);
        $final = imagecreatetruecolor($tn_w, $tn_h);
        imagecopyresampled($final, $newpic, 0, 0, $x_mid - $tn_w / 2, $y_mid - $tn_h / 2, $tn_w, $tn_h, $tn_w, $tn_h);
        if($imgtype == 'image/jpeg' || $imgtype == 'image/jpg' || $imgtype == 'image/JPG' || $imgtype == 'image/JPEG'){
          imagejpeg($final, $target.$filename, $quality);
          return $filename;
        }elseif($imgtype == 'image/png' || $imgtype == 'image/PNG'){
          imagepng($final, $target.$filename, 5);
          return $filename;
        }
        return [
            'status' => false,
            'code'   => 'Sorry an error occurred',
        ];
    }

    /**
     * Watermark image
     * 
     * @param mixed $source_file image file
     * @param mixed $wtrmrk_file watermark file
     * @param mixed $target upload folder
     * @param mixed $position="center" watermark position
     * 
     * @return [type]
     */
    public function Watermark($source_file, $wtrmrk_file, $target, $position="center")
    {
      $watermark = imagecreatefrompng($wtrmrk_file);
      imagealphablending($watermark, false);
      imagesavealpha($watermark, true);
      $info = getimagesize($source_file);
      $imgtype = image_type_to_mime_type($info[2]);
      switch ($imgtype) {
          case 'image/jpeg':
              $img = imagecreatefromjpeg($source_file);
              break;
          case 'image/JPEG':
              $img = imagecreatefromjpeg($source_file);
              break;
          case 'image/jpg':
              $img = imagecreatefromjpeg($source_file);
              break;
          case 'image/JPG':
              $img = imagecreatefromjpeg($source_file);
              break;
          case 'image/gif':
              $img = imagecreatefromgif($source_file);
              break;
          case 'image/GIF':
              $img = imagecreatefromgif($source_file);
              break;
          case 'image/png':
              $img = imagecreatefrompng($source_file);
              break;
          case 'image/PNG':
              $img = imagecreatefrompng($source_file);
              break;
          default:
            return [
                'status' => false,
                'code'   => 'This image is invalid',
            ];
      }
      $img_w = imagesx($img);
      $img_h = imagesy($img);
      $wtrmrk_w = imagesx($watermark);
      $wtrmrk_h = imagesy($watermark);
      if($position == "center"){
        $dst_x = $img_w / 2 - $wtrmrk_w / 2; 
        $dst_y = $img_h / 2 - $wtrmrk_h / 2;
      }elseif($position == "top-left"){
        $dst_x = -45; 
        $dst_y = -5;
      }elseif($position == "top-right"){
        $dst_x = imagesx($img) - $wtrmrk_w + 45; 
        $dst_y = -5;
      }elseif($position == "bottom-left"){
        $dst_x = -45; 
        $dst_y = imagesy($img) - $wtrmrk_h + 5;
      }elseif($position == "bottom-right"){
        $dst_x = imagesx($img) - $wtrmrk_w + 45; 
        $dst_y = imagesy($img) - $wtrmrk_h + 5;
      }
      imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
      switch ($imgtype) {
          case 'image/jpeg':
              imagejpeg($img, $target, 100);
              break;
          case 'image/JPEG':
              imagejpeg($img, $target, 100);
              break;
          case 'image/jpg':
              imagejpeg($img, $target, 100);
              break;
          case 'image/JPG':
              imagejpeg($img, $target, 100);
              break;
          case 'image/gif':
              imagegif($img, $target, 100);
              break;
          case 'image/GIF':
              imagegif($img, $target, 100);
              break;
          case 'image/png':
              imagepng($img, $target, 6);
              break;
          case 'image/PNG':
              imagepng($img, $target, 6);
              break;
      }
      imagedestroy($img);
      imagedestroy($watermark);
      return $target;
    }

    /**
     * Open the file.
     *
     * @param (string) $name Name of file
     * @param (string) $mode Mode of file
     *
     * @return resource
     */
    public function open($name, $mode)
    {
        if (!empty(trim($name))) {
            $this->resource = fopen($name, $this->modes[$mode]);

            return $this;
        }
    }

    /**
     * Read the file.
     *
     * @param (string) $file File that to be read
     *
     * @return file
     */
    public function read($file)
    {
        return fread($this->resource, filesize($file));
    }

    /**
     * Write on file.
     *
     * @param (string) $data Data that you want write on file
     *
     * @return bool
     */
    public function write($data)
    {
        return (!empty($data)) ? fwrite($this->resource, $data) : false;
    }

    /**
     * Delete the file.
     *
     * @param $file file to be deleted
     *
     * @return bool
     */
    public function delete($file)
    {
        return (file_exists($file)) ? unlink($file) : false;
    }

    /**
     * generate salts for files.
     *
     * @param (string) $length Length of salts
     *
     * @return string
     */
    public static function rendomFileName($length)
    {
        $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $stringlength = count($chars); //Used Count because its array now
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $stringlength - 1)];
        }

        return $randomString;
    }
}
