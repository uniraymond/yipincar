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
    return response()->Json();
  }
}
