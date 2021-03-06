<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use \Response as Response;
use \Storage as Storage;
use \Input as Input;
use \DB as DB;

class ResourceController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('resources/index');
  }
  public function show($id)
  {
//    return view('resources/show');
  }

  public function edit($id)
  {
      return view('resources/edit');
  }

  public function upload(Request $request)
  {
    $authuser = $request->user();
    $file = $request->file('file');

    if(!empty($file)) {
      $fileName = $file->getClientOriginalName();
//      Storage::put($fileName, file_get_contents($file));
      $fileDir = "resources";
      $file->move($fileDir, $fileName);
      DB::table('resources')->insert(
          [
              'name' => $fileName,
              'description' => $request->input('description'),
              'link' => $fileDir.'/'.$fileName,
              'type_id' => 1,
              'published' => 1,
              'created_by' => $authuser->id
          ]
      );
    }

//        $image = Image::make(sprintf($fileDir.'/'.$fileName));
//        $imageSize = GetImageSize($fileDir.'/'.$fileName);
//        $markWidth = ($imageSize[0] > $imageSize[1] ? $imageSize[1] : $imageSize[0]) - 20;
//        $waterMark = Image::make(sprintf('photos/watermark2.png'))->resize($markWidth -40, $markWidth -40);
//        $image->insert($waterMark, 'center');
//        $image->save();

    $a = array(
        'filename'=>$file->getClientOriginalName(),
        'path'=>storage_path($file->getClientOriginalName()),
        'userId' => $authuser->id,
    );
    return response()->json($a);
  }
}
