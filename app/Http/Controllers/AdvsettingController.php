<?php

namespace App\Http\Controllers;

use App\AdvPosition;
use App\AdvType;
use App\ArticleResources;
use App\ArticleStatus;
use App\Category;
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
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('category_id', '<>', 0)->get();
    $advSettings = AdvSetting::where('type_id', '<>', 0)->paginate(15);
    return view('advsetting/index', ['advsettings' => $advSettings, 'types'=>$types, 'positions'=>$positions, 'categories'=>$categories]);
  }

//  public function type(Request $request, $typeId)
//  {
//    $types = ResourceTypes::all();
//    $images = Resource::where('type_id', $typeId)->paginate(15);
//    return view('advsetting/index', ['images' => $images, 'types'=>$types]);
//  }


  public function update(Request $request)
  {
    $authuser = $request->user();

    $orders = $request['order'];
    foreach ($orders as $advId => $order) {
      $oimage = AdvSetting::findorFail($advId);
      if($oimage) {
        $oimage->order = $order;
        $oimage->save();
      }
    }
    $deleteIds = $request['delete'];
    if (count($deleteIds)>0){
      foreach ($deleteIds as $key => $deleteId) {
        $advsetting = AdvSetting::findorFail($key);
        $advsetting->delete();
      }
    }


    $request->session()->flash('status', '广告已更新.');
    return redirect('admin/advsetting/list');
  }

  public function edit($id)
  {
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('last_category', 1)->get();
    $advSettings = AdvSetting::findorFail($id);
    $displayorder = ArticleResources::where('resource_id', $id)->where('article_id', 0)->first();

    return view('advsetting/editimage', ['advSettings' => $advSettings, 'types'=>$types, 'displayorder'=>$displayorder, 'positions'=>$positions, 'categories'=>$categories]);
  }

    public function show($id)
    {
        $allStatusChecks = array();
        $advSetting = AdvSetting::findorFail($id);

        $articleStatus = $advSetting->article_statuses;
        $checks = $advSetting->article_status_checks;
        $allStatuses = ArticleStatus::orderBy('id', 'desc')->get();

        return view('advsetting/show', ['advSetting'=>$advSetting, 'allStatusChecks'=>$allStatusChecks]);
    }

  public function create(Request $request)
  {
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('last_category', 1)->get();
    return view('advsetting/createimage', ['types'=>$types, 'positions'=>$positions, 'categories'=>$categories]);
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

    $advSetting = AdvSetting::findorFail($request['id']);

    $advSetting->title = $request['title'];
    $advSetting->type_id = $request['type_id'];
    $advSetting->position_id = $request['position_id'];
    $advSetting->category_id = $request['category_id'];
    $advSetting->description = $request['description'];
    $advSetting->order = $request['order'];
    $advSetting->links = $request['links'];
    $advSetting->published_at = date('Y-m-d');
    $advSetting->created_by = $authuser->id;
    $advSetting->save();

    $request->session()->flash('status', '成功更新广告');
    return redirect('admin/advsetting/list');
  }

  public function uploadimage(Request $request)
  {
    $this->validate($request, [
        'images' => 'required',
    ]);
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
      $resource->name = $file->getClientOriginalName();
      $resource->link = $imageLink;
      $resource->created_by = $authuser->id;
      $resource->save();
      
      
      $advSetting = new AdvSetting();
      $advSetting->title = $request['title'];
      $advSetting->resource_id = $resource->id;
      $advSetting->type_id = $request['type_id'];
      $advSetting->position_id = $request['position_id'];
      $advSetting->category_id = $request['category_id'];
      $advSetting->description = $request['description'];
      $advSetting->order = $request['order'];
      $advSetting->links = $request['links'];
      $advSetting->published_at = date('Y-m-d');
      $advSetting->created_by = $authuser->id;
      $advSetting->save();
    }

    $request->session()->flash('status', '图片上传成功.');
    return redirect('admin/advsetting/list');
  }

  public function type($id)
  {
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('category_id', '<>', 0)->get();
    $advSettings = AdvSetting::where('type_id', $id)->paginate(15);
    return view('advsetting/index', ['advsettings' => $advSettings, 'types'=>$types, 'positions'=>$positions, 'categories'=>$categories]);
  }

  public function position($id)
  {
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('category_id', '<>', 0)->get();
    $advSettings = AdvSetting::where('position_id', $id)->paginate(15);
    return view('advsetting/index', ['advsettings' => $advSettings, 'types'=>$types, 'positions'=>$positions, 'categories'=>$categories]);

  }
}
