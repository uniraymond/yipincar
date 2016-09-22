<?php

namespace App\Http\Controllers;

use App\AdvPosition;
use App\AdvType;
use App\ArticleResources;
use App\ArticleStatus;
use App\ArticleStatusCheck;
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

    public function show(Request $request, $id)
    {
        $allStatusChecks = array();
        $advsetting = AdvSetting::findorFail($id);

        $articleStatus = $advsetting->article_statuses;
        $checks = $advsetting->article_status_checks;
        $allStatuses = ArticleStatus::orderBy('id', 'desc')->get();

        foreach($allStatuses as $status) {
            $allStatusChecks[$status->name] = $this->getAdvSettingStatusObject($status->id, $id);
        }

        return view('advsetting/show', ['advsetting'=>$advsetting, 'allStatusChecks'=>$allStatusChecks]);
    }

    private function getAdvSettingStatusObject($status_id, $adv_setting_id) {
        $article_status = ArticleStatusCheck::where('article_status_id', $status_id)->where('adv_setting_id', $adv_setting_id)->get();
        return $article_status;
    }

    /*
    *   articleStatusChecked
     *  article_status_id is the process of review for example if main editor reviewed this value should be 3 - review
     *  checked is current adv or article status 0,1 is reject; 2 is apply reivew 3. is final reviewed 4. is published
    */
    public function newreview(Request $request, $advsettingid)
    {
        $authuser = $request->user();
        $articleStatus = ArticleStatus::where('name', $request['article_status'])->first();

        $articleStatusCheck = new ArticleStatusCheck();
        $articleStatusCheck->adv_setting_id = $advsettingid;
        $articleStatusCheck->article_status_id = $articleStatus->id;
        $articleStatusCheck->comment = $request['comment'];
        $articleStatusCheck->checked = 1;
        switch($request['article_status']) {
            case 'publish':
                if($request['status']) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['status']) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['status']) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['status']) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }

        $articleStatusCheck->created_by = $authuser->id;
        $articleStatusCheck->save();

        $advsetting = AdvSetting::find($advsettingid);
        $advsetting->status = $articleStatusCheck->checked;
        $advsetting->save();

        $request->session()->flash('status', '添加了新的广告评估.');
        return redirect('admin/advsetting/show/'.$advsettingid);
    }

    public function editreview(Request $request, $advsettingid, $id)
    {
        $allArticleStatus = ArticleStatus::all();
        foreach ($allArticleStatus as $allAS) {
            $allStatus[$allAS->id] = $allAS->name;
        }
        $authuser = $request->user();
//    $articleStatus = ArticleStatus::where('name', $request['article_status'])->first();
        $articleStatusId = array_search($request['article_status'], $allStatus);
        $statusReview = array_search('review', $allStatus);

        $articleStatusCheck = ArticleStatusCheck::find($id);
        $articleStatusCheck->comment = $request['comment'];
        $articleStatusCheck->updated_by = $authuser->id;
        $articleStatusCheck->checked = 1;
        switch($request['article_status']) {
            case 'publish':
                if($request['status']) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['status']) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['status']) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['status']) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }

        $articleStatusCheck->save();

        $article = AdvSetting::find($advsettingid);
        $article->status =  $articleStatusCheck->checked;
        $article->save();

        $request->session()->flash('status', '更新了广告评估.');
        return redirect('admin/advsetting/show/'.$advsettingid);
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

    public function checktop($id)
    {
        $totalTop = 0;
        $advSettings = AdvSetting::where('top', 1)->get();
        $articles = Article::where('top', 1)->get();
        $totalTop = count($articles) + count($advSettings);

        if ($totalTop > 6) {
            return json_encode('error');
        }
        return json_encode('success');
    }
}
