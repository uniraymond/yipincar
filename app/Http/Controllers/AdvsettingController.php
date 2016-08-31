<?php

namespace App\Http\Controllers;

use App\ArticleResources;
use App\Resource;
use App\ResourceTypes;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB as DB;
use App\AdvSetting;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\File;

class AdvsettingController extends Controller
{
  /**
   * @return mixed
   */
  public function index()
  {
//    $images = AdvSetting::getAdvImages();
    $types = ResourceTypes::all();
    $images = Resource::where('type_id', '<>', 0)->paginate(15);
    return view('advsetting/index', ['images' => $images, 'types'=>$types]);
  }

  public function type(Request $request, $typeId)
  {
    $types = ResourceTypes::all();
    $images = Resource::where('type_id', $typeId)->paginate(15);
    return view('advsetting/index', ['images' => $images, 'types'=>$types]);
  }


  public function update(Request $request)
  {
    $authuser = $request->user();
    $deleteIds = $request['delete'];
    foreach ($deleteIds as $key => $deleteId) {
      $image = Resource::findorFail($key);
      $image->delete();
    }
    $orders = $request['order'];
    foreach ($orders as $imageId => $order) {
      $oimage = Resource::findorFail($imageId);
      $oimage->order = $order;
      $oimage->save();
    }

    $request->session()->flash('filestatus', '广告已更新.');
    return redirect('admin/advsetting/list');
  }

  public function edit($id)
  {
    $image = Resource::findorFail($id);
    $types = ResourceTypes::all();
    $displayorder = ArticleResources::where('resource_id', $id)->where('article_id', 0)->first();

    return view('advsetting/editimage', ['image' => $image, 'types'=>$types, 'displayorder'=>$displayorder]);
  }

  public function create(Request $request)
  {
    $types = ResourceTypes::all();
    return view('advsetting/createimage', ['types'=>$types]);
  }

  /**
   * @param Request $request
   * @return mixed
   */

  public function updateimage(Request $request)
  {
    $authuser = $request->user();

    $this->validate($request, [
        'id' => 'required',
    ]);

    $image = Resource::findorFail($request['id']);
    $image->name = $request['name'];
    $image->description = $request['description'];
    $image->type_id = $request['type_id'];
    $image->order = $request['order'];
    $image->published = $request['published'] ? 1 : 0;
    $image->updated_by = $authuser->id;
    $image->save();

    $request->session()->flash('filestatus', '成功更新广告');
    return redirect('admin/advsetting/list');
  }

  public function uploadimage(Request $request)
  {
    $authuser = $request->user();
    $file = $request->file('images');
    if (!empty($file)) {
      $fileName = $file->getClientOriginalName();
//      $fileName = $request['name'] ? $request['name'] : $file->getClientOriginalName(); //if wanna use filled file name use this line

      $fileDir = "photos/adv";
      $file->move($fileDir, $fileName);
      $imageLink = $fileDir . '/' . $file->getClientOriginalName();
      $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same

      $image = Image::make(sprintf('photos/adv/%s', $file->getClientOriginalName()))->resize(500, (int)((500 * $cell_img_size[1]) / $cell_img_size[0]))->save();
      $resource = new Resource();
      $resource->name = $request['name'];
      $resource->description = $request['description'];
      $resource->link = $imageLink;
      $resource->type_id = $request['type_id'];
      $resource->order = $request['order'];
      $resource->published = $request['published'] ? 1 : 0;
      $resource->created_by = $authuser->id;
      $resource->save();
    }

    $request->session()->flash('filestatus', '图片上传成功.');
    return redirect('admin/advsetting/list');
  }
}
