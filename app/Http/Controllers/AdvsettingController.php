<?php

namespace App\Http\Controllers;

use App\AdvPosition;
use App\AdvSettingResources;
use App\AdvTemplate;
use App\AdvType;
use App\Article;
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
    public function generateImageName($file, $order){
        $fartime = strtotime('2300-12-30');
        $nowtime  = strtotime('now');
        $new_name = ($fartime - $nowtime).'ypc-'.$order;
        $new_filename = $new_name . '.' . $file->getClientOriginalExtension();
        return $new_filename;
    }

    /**
   * @return mixed
   */
  public function index()
  {
//    $images = AdvSetting::getAdvImages();
    $types = AdvType::all();
    $positions = AdvPosition::all();
    $categories = Category::where('category_id', '<>', 0)->get();
    $advSettings = AdvSetting::where('type_id', '<>', 0)->orderBy('top', 'desc')->orderBy('created_at', 'desc')->paginate(15);
      $totalAdvs = AdvSetting::count();
    $totalTop = $this->getTotalTop();
    return view('advsetting/index', ['advsettings' => $advSettings, 'types'=>$types, 'positions'=>$positions, 'categories'=>$categories, 'totalAdvs'=>$totalAdvs, 'totalTop'=>$totalTop]);
  }

//  public function type(Request $request, $typeId)
//  {
//    $types = ResourceTypes::all();
//    $images = Resource::where('type_id', $typeId)->paginate(15);
//    return view('advsetting/index', ['images' => $images, 'types'=>$types]);
//  }

  public function getTotalTop()
  {
    $totalTop = 0;
    $advSettings = AdvSetting::where('top', 1)->get();
    $articles = Article::where('top', 1)->get();
    $totalTop = count($articles) + count($advSettings);

    return ($totalTop);
  }

  public function update(Request $request)
  {
    $authuser = $request->user();

    $orders = $request['order'];
    $tops = $request['top'];
    foreach ($orders as $advId => $order) {
      $oimage = AdvSetting::findorFail($advId);
      if($oimage) {
        $oimage->order = $order;
        $oimage->top = isset($tops[$advId]) && $tops[$advId] ? 1 : 0;
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
      $templates = AdvTemplate::all();
    $categories = Category::where('last_category', 1)->get();
    $advSettings = AdvSetting::findorFail($id);
    $displayorder = ArticleResources::where('resource_id', $id)->where('article_id', 0)->first();

    return view('advsetting/editimage', ['advSettings' => $advSettings, 'types'=>$types, 'displayorder'=>$displayorder, 'positions'=>$positions, 'categories'=>$categories, 'templates'=>$templates]);
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
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }

        $articleStatusCheck->created_by = $authuser->id;
        $articleStatusCheck->save();

        $advsetting = AdvSetting::find($advsettingid);
        if ($request['status'] >= 1) {
            $advsetting->status = $articleStatusCheck->checked;
        }
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
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 4;
                }
                break;
            case 'review':
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 3;
                }
                break;
            case 'review_apply':
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
            default:
                if($request['status'] == 2) {
                    $articleStatusCheck->checked = 2;
                }
                break;
        }

        $articleStatusCheck->save();

        $article = AdvSetting::find($advsettingid);
        if ($request['status'] >= 1){
            $article->status =  $articleStatusCheck->checked;
        }
        $article->save();

        $request->session()->flash('status', '更新了广告评估.');
        return redirect('admin/advsetting/show/'.$advsettingid);
    }


  public function create(Request $request)
  {
        $types = AdvType::all();
        $positions = AdvPosition::all();
        $templates = AdvTemplate::all();
        $categories = Category::where('last_category', 1)->get();

      return view('advsetting/createimage', ['types'=>$types, 'positions'=>$positions, 'categories'=>$categories, 'templates'=>$templates]);
  }

  /**
   * @param Request $request
   * @return mixed
   */

  public function updateimage(Request $request)
  {
    $authuser = $request->user();

    $this->validate($request, [
//        'id' => 'required',
        'title' => 'required|max:36',
        'links' => 'required|URL',
        'images1' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
        'images2' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
        'images3' => 'max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
//        'images1' => 'required|image',
//        'images2' => 'required_if:temmplate_radio,3|image',
//        'images3' => 'required_if:temmplate_radio,3|image',
    ], $this->messages());

      $advSetting = AdvSetting::findorFail($request['id']);

      $advSetting->title = $request['title'];
      $advSetting->type_id = $request['type_id'];
      $advSetting->position_id = $request['position_id'];
      $advSetting->template_id = $request['temmplate_radio'];
      $advSetting->category_id = $request['category_id'];
      $advSetting->description = $request['description'];
      $advSetting->identification = $request['identification'];
      $advSetting->order = $request['order'];
      $advSetting->links = $request['links'];
      $advSetting->published_at = date('Y-m-d');
      $advSetting->created_by = $authuser->id;
      if (isset($request['status'])) {
          $advSetting->status = $request['status'] ? 2 : 1;
      }

      if (isset($request['top'])) {
          $advSetting->top = $request['top'] ? 1 : 0;
      }
      $advSetting->save();

      $file = $request->file('images1');

      if (!empty($file)) {
          $this->saveAdvIconWith($file, $authuser, $advSetting, true, 1);
      }

      if($advSetting->template_id == 3) {
          $file = $request->file('images2');
          if(!empty($file)) {
              $this->saveAdvIconWith($file, $authuser, $advSetting, true, 2);
          }
          $file = $request->file('images3');
          if(!empty($file)) {
              $this->saveAdvIconWith($file, $authuser, $advSetting, true, 3);
          }
      }

    $request->session()->flash('status', '成功更新广告');
    return redirect('admin/advsetting/list');
  }

  public function uploadimage(Request $request)
  {
      $this->validate($request, [
          'title' => 'required|max:36',
          'links' => 'required|URL',
//          'images'=> 'required|image',
          'images1' => 'required|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
          'images2' => 'required_if:temmplate_radio,3|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
          'images3' => 'required_if:temmplate_radio,3|max:2048000|mimes:jpg,gif,bmp,bnp,png,mif,mif,jpeg,ico,tif,tiff,cut,pic,tga,dib,svg,eps',
      ], $this->messages());
    $authuser = $request->user();
    $file = $request->file('images1');

    if (!empty($file)) {
//      $fileOriginalName = $file->getClientOriginalName();
//      $fileName = $this->generateImageName($file, 1);
////      $fileName = $request['name'] ? $request['name'] : $file->getClientOriginalName(); //if wanna use filled file name use this line
//
//      $fileDir = "photos/adv";
//      $file->move($fileDir, $fileName);
////      $imageLink = $fileDir . '/' . $file->getClientOriginalName();
//      $imageLink = $fileDir . '/' . $fileName;
//      $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same
//
////      $image = Image::make(sprintf('photos/adv/%s', $file->getClientOriginalName()))->resize(800, (int)((800 * $cell_img_size[1]) / $cell_img_size[0]))->save();
//      $image = Image::make(sprintf('photos/adv/%s', $fileName))->save();
//      $resource = new Resource();
//      $resource->name = $fileName;
//      $resource->link = '/' . $imageLink;
//      $resource->created_by = $authuser->id;
//      $resource->save();

        $advSetting = new AdvSetting();
        $advSetting->title = $request['title'];
//      $advSetting->resource_id = $resource->id;
        $advSetting->type_id = $request['type_id'];
        $advSetting->position_id = $request['position_id'];
        $advSetting->template_id = $request['temmplate_radio'];
        $advSetting->category_id = $request['category_id'];
        $advSetting->description = $request['description'];
        $advSetting->identification = $request['identification'];
        $advSetting->order = $request['order'];
        $advSetting->links = $request['links'];
        $advSetting->status = $request['status'] ? 2 : 1;
        $advSetting->top = $request['top'] ? 1 : 0;
        $advSetting->published_at = date('Y-m-d');
        $advSetting->created_by = $authuser->id;
        $advSetting->readed = random_int(8000, 15000);
        $advSetting->save();

        $this->saveAdvIconWith($file, $authuser, $advSetting, false, 1);

        if($advSetting->template_id == 3) {
            $file = $request->file('images2');
            $this->saveAdvIconWith($file, $authuser, $advSetting, false, 2);
            $file = $request->file('images3');
            $this->saveAdvIconWith($file, $authuser, $advSetting, false, 3);
        }

        if ($request['status']) {
            $articleStatusCheck = new ArticleStatusCheck();
            $articleStatusCheck->adv_setting_id = $advSetting->id;
            $articleStatusCheck->article_status_id = 3;
            $articleStatusCheck->checked = 2;
            $articleStatusCheck->created_by = $authuser->id;
            $articleStatusCheck->save();
        }
    }

    $request->session()->flash('status', '推广添加成功.');
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

    public function checktop()
    {
        $totalTop = 0;
        $advSettings = AdvSetting::where('top', 1)->get();
        $articles = Article::where('top', 1)->get();
        $totalTop = count($articles) + count($advSettings);

        if ($totalTop >= 6) {
            $arr = array('status'=>'faild');
            return response()->Json($arr);
        }
        $arr = array('status'=>'success');
        return response()->Json($arr);
    }

    public function messages()
    {
        return [
            'title.required' => '标题是必填的',
            'title.max' => '标题不能超过35个字',
            'links.required' => '链接是必须的',
            'links.u_r_l' => '请填写正确的链接格式',
            'images1.required' => '至少添加一张图片',
            'images1.mimes' => '图1请上传正确的图片格式',
            'images2.mimes' => '图2请上传正确的图片格式',
            'images3.mimes' => '图3请上传正确的图片格式',
            'images2.required_if' => '选择3图模板,则必须添加图片2',
            'images3.required_if' => '选择3图模板,则必须添加图片3',
            'images1.max' => '上传图片1不能超过20M',
            'images2.max' => '上传图片2不能超过20M',
            'images3.max' => '上传图片3不能超过20M',
        ];
    }

    public function saveAdvIconWith($file, $authuser, $adv_setting, $update, $order) {
        $fileOriginalName = $file->getClientOriginalName();
        $fileName = $this->generateImageName($file, $order);
        $fileOriginalDir = "photos/adv/original";
        $fileThumbsDir = "photos/adv/thumbs";
        $fileDir = "photos/adv";

        $file->move($fileDir, $fileName);

        $imageOriginalLink = $fileOriginalDir . '/' . $fileName;
        $imageThumbsLink = $fileThumbsDir . '/' . $fileName;
        $imageLink = $fileDir . '/' . $fileName;
        copy($imageLink, $imageThumbsLink);
        copy($imageLink, $imageOriginalLink);

        $cell_img_size = GetImageSize($imageLink); // need to caculate the file width and height to make the image same

        $image = Image::make(sprintf('photos/adv/%s', $fileName))->save();
        $imageThumbs = Image::make(sprintf('photos/adv/thumbs/%s', $fileName))->resize(300, (int)((300 *  $cell_img_size[1]) / $cell_img_size[0]))->save();
        $imageOriginal = Image::make(sprintf('photos/adv/original/%s', $fileName))->save();

        if($update) {
            $oldArticlResource = AdvSettingResources::where('adv_setting_id', $adv_setting->id)
                ->where('displayorder', $order)
                ->delete();
            Resource::where('link', '/' . $imageLink)->delete();
        }

        $resource = new Resource();
        $resource->name = $fileName;
        $resource->link = '/' . $imageLink;
        $resource->created_by = $authuser->id;
        $resource->order = $order;
        $resource->save();

        $advResource = new AdvSettingResources();
        $advResource->adv_setting_id = $adv_setting->id;
        $advResource->resource_id = $resource->id;
        $advResource->displayorder = $order;
        $advResource->save();



    }

}
