<?php namespace Unisharp\Laravelfilemanager\controllers;

use Illuminate\Support\Facades\Event;
use Unisharp\Laravelfilemanager\controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Lang;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Unisharp\Laravelfilemanager\Events\ImageWasUploaded;
use App\Resource;
use Illuminate\Http\Request;

/**
 * Class UploadController
 * @package Unisharp\Laravelfilemanager\controllers
 */
class UploadController extends LfmController {

    private $default_file_types = ['application/pdf'];
    private $default_image_types = ['images/jpeg', 'images/png', 'images/gif','image/jpeg', 'image/png', 'image/gif'];

    /**
     * Upload an images/file and (for images) create thumbnail
     *
     * @param UploadRequest $request
     * @return string
     */
    public function upload()
    {
        $org_folder_name = Config::get('lfm.org_folder_name');

        try {
            $res = $this->uploadValidator();
            if (true !== $res) {
                return Lang::get('laravel-filemanager::lfm.error-invalid');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $file = Input::file('upload');
        $new_filename = $this->getNewName($file);

        $dest_path = parent::getPath('directory');
        $currentFile = str_replace('//', '/', $dest_path.'/'.$new_filename);

        $org_folder = $dest_path . $org_folder_name;
        $org_folder = str_replace('//', '/', $org_folder);

        $org_file = str_replace('//', '/', $org_folder.'/'.$new_filename);
        if (!File::exists($org_folder)) {
            File::makeDirectory($org_folder);
        }

        if (File::exists($org_folder . $new_filename)) {
            return Lang::get('laravel-filemanager::lfm.error-file-exist');
        }

        $file->move($dest_path, $new_filename);
        copy($currentFile, $org_file);

        if ('Images' === $this->file_type) {
            $this->makeThumb($dest_path, $new_filename);
            $this->makeCell($dest_path, $new_filename);
        }

        Event::fire(new ImageWasUploaded(realpath($org_file)));

        // upload via ckeditor 'Upload' tab
        if (!Input::has('show_list')) {
            return $this->useFile($new_filename);
        }

        $img_size = GetImageSize($org_file);
        $maxSize = 480;
        if($img_size[0] > $maxSize) {
            Image::make($org_file)
                ->fit($maxSize, (int)(($maxSize * $img_size[1]) / $img_size[0]))
                ->save($currentFile);
        }

        $fileUrl = str_replace('//', '/', parent::getUrl().'/'.$new_filename);

        $file = new Resource();
        $file->name = $new_filename;
        $file->link = $fileUrl;

        $file->save();

        return 'OK';
    }

    private function uploadValidator()
    {
        // when uploading a file with the POST named "upload"

        $expected_file_type = $this->file_type;
        $is_valid = false;

        $file = Input::file('upload');
        if (empty($file)) {
            throw new \Exception(Lang::get('laravel-filemanager::lfm.error-file-empty'));
        }
        if (!$file instanceof UploadedFile) {
            throw new \Exception(Lang::get('laravel-filemanager::lfm.error-instance'));
        }

        $mimetype = $file->getMimeType();

        if ($expected_file_type === 'Files') {
            $config_name = 'lfm.valid_file_mimetypes';
            $valid_mimetypes = Config::get($config_name, $this->default_file_types);
        } else {
            $config_name = 'lfm.valid_image_mimetypes';
            $valid_mimetypes = Config::get($config_name, $this->default_image_types);
        }

        if (!is_array($valid_mimetypes)) {
            throw new \Exception('Config : ' . $config_name . ' is not set correctly');
        }

        if (in_array($mimetype, $valid_mimetypes)) {
            $is_valid = true;
        }

        if (false === $is_valid) {
            throw new \Exception(Lang::get('laravel-filemanager::lfm.error-mime') . $mimetype);
        }
        return $is_valid;
    }

    private function getNewName($file)
    {
        $new_filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $fartime = strtotime('2300-12-30');
        $nowtime  = strtotime('now');
        $new_name = ($fartime - $nowtime).'ypc';
        $new_filename = $new_name . '.' . $file->getClientOriginalExtension();

        /*   if (Config::get('lfm.rename_file') === true) {
               $new_filename = uniqid();
           } elseif (Config::get('lfm.alphanumeric_filename') === true) {
               $new_filename = preg_replace('/[^A-Za-z0-9\-\']/', '_', $new_filename);
           }

           $new_filename = $new_filename . '.' . $file->getClientOriginalExtension();
   */
        return $new_filename;
    }

    private function makeThumb($dest_path, $new_filename)
    {
        $thumb_folder_name = Config::get('lfm.thumb_folder_name');

        // get orignal file path
        $org_file = $dest_path .$new_filename;
        $org_file = str_replace('//', '/', $org_file);

        //get thumb folder path
        $thumb_folder = $dest_path . $thumb_folder_name;
        $thumb_folder = str_replace('//', '/', $thumb_folder);

        if (!File::exists($thumb_folder)) {
            File::makeDirectory($thumb_folder);
        }
        $thumb_file = str_replace('//', '/', $thumb_folder.'/'.$new_filename);

        $thumb_img_size = GetImageSize($org_file);
        $thumb_img = Image::make($org_file)
            ->fit(190, (int)((190 * $thumb_img_size[1]) / $thumb_img_size[0]))
            ->save($thumb_file);
        unset($thumb_img);
    }

    private function makeCell($dest_path, $new_filename)
    {
        $cell_folder_name = Config::get('lfm.cell_folder_name');

        //wanna use cell phone size file on current file folder
//        if (!File::exists($dest_path . $cell_folder_name)) {
//            File::makeDirectory($dest_path . $cell_folder_name);
//        }

        $org_file = $dest_path .$new_filename;
        $org_file = str_replace('//', '/', $org_file);

        $cell_img_size = GetImageSize($org_file);

        $cell_img = Image::make($org_file)
            // ->fit(300, (int)((300 * $cell_img_size[1]) / $cell_img_size[0]))
            ->save($org_file);
        unset($cell_img);
    }

    private function useFile($new_filename)
    {
        $file = parent::getUrl('directory') . $new_filename;

        return "<script type='text/javascript'>

        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);
            return ( match && match.length > 1 ) ? match[1] : null;
        }

        var funcNum = getUrlParam('CKEditorFuncNum');

        var par = window.parent,
            op = window.opener,
            o = (par && par.CKEDITOR) ? par : ((op && op.CKEDITOR) ? op : false);

        if (op) window.close();
        if (o !== false) o.CKEDITOR.tools.callFunction(funcNum, '$file');
        </script>";
    }

}
