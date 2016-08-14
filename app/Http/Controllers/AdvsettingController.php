<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB as DB;
use App\AdvSetting;

class AdvsettingController extends Controller
{
  /**
   * @return mixed
   */
  public function index()
  {
    $images = AdvSetting::getAdvImages();
    return view('advsetting/index', ['images'=>$images]);
  }

  public function update(Request $request)
  {
    $authuser = $request->user();
    AdvSetting::updateAllImages($request, $authuser->id);

    $request->session()->flash('filestatus', 'Update Successful!');
    return redirect('admin/advsetting/list');
  }

  public function editimage($id)
  {
    $image = AdvSetting::getAdvImage($id);
    return view('advsetting/editimage', ['images'=>$image]);
  }

  /**
   * @param Request $request
   * @return mixed
   */

  public function updateImage(Request $request)
  {
    $this->validate($request, [
      'id' => 'required',
    ]);
    $id = $request['id'];
    $descrition = $request['description'];

    if ($list = AdvSetting::updateImage($request)) {
      $request->session()->flash('status', 'Update Successful!');
//      return response()->Json(array('success'=>true, 'imageDes'=>$descrition, 'id'=>$id));
      return redirect('admin/advsetting/list');
    } else {

      //need to validate
      return response()->Json(array('success'=>false));
    }
  }

  public function uploadImage(Request $request)
  {
    $authuser = $request->user();
    $file = $request->file('images');
    if(!empty($file)) {
      $fileName = $request['name'] ? $request['name'] : $file->getClientOriginalName();
//      Storage::put($fileName, file_get_contents($file));
      $fileDir = "resources";
      $file->move($fileDir, $fileName);
      DB::table('resources')->insert(
          [
              'name' => $fileName,
              'description' => $request->input('description'),
              'link' => $fileDir.'/'.$file->getClientOriginalName(),
              'type_id' => 1,
              'published' => 1,
              'created_by' => $authuser->id
          ]
      );
    }

    $request->session()->flash('filestatus', 'Update Successful!');
    return redirect('admin/advsetting/list');
//    $a = array(
//        'filename'=>$file->getClientOriginalName(),
//        'path'=>storage_path($file->getClientOriginalName()),
//        'userId' => $authuser->id,
//    );
//    return response()->json($a);
  }
}
