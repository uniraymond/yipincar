<?php

namespace App\Http\Controllers;

use App\Car;
use App\CarBrand;
use Illuminate\Http\Request;

use App\Http\Requests;

class CarController extends Controller
{
    public function carDetail($id) {
        return view('shopping/cardetail');
    }

    public function getCarBrand() {
        $url='http://api.jisuapi.com/car/brand?appkey=197a3b832c400da4';
            $html = json_decode(file_get_contents($url));
            $results = $html->result;
        for ($i=0; $i < count($results); $i++) {
            $array = $this->object_array($results[$i]);
//            dd(array_merge($array, ['jisu_id' => $array['id']]));
            CarBrand::create(array_merge($array, ['jisu_id' => $array['id']]));
        }
    }

    public function getCars() {
        $brands = CarBrand::all();
        for ($k=0; $k<count($brands); $k++) {
            $brandId = $brands[$k]['jisu_id'];
            $url = 'http://api.jisuapi.com/car/type?appkey=197a3b832c400da4&parentid='.$brandId;
            $html = json_decode(file_get_contents($url));
            $results = $html->result;
            for ($i=0; $i < count($results); $i++) {
                $array = $this->object_array($results[$i]);
//            dd(array_merge($array, ['jisu_id' => $array['id']]));
                Car::create(array_merge($array, ['jisu_id' => $array['id'], 'parentid' => $brandId]));
                $lists = $array['list'];
                if ($lists) {
                    for ($j=0; $j<count($lists); $j++) {
                        $car = $this->object_array($lists[$j]);
                        Car::create(array_merge($car, ['jisu_id' => $array['id'], 'parentid' => $array['id']]));
                    }
                }
            }
        }
    }

    public function getBrandLogo() {
        $brands = CarBrand::all();
        for ($k=0; $k<count($brands); $k++) {
            $brand = $brands[$k];
            $url = $brand['logo'];
//            $names = explode($url, ".");
//            $extention = $names[count($names) -1];
            if ($url != null && strlen($url) > 0) {
                $savePath = public_path()."/photos/cars/brands/logo/".$brand['id'].'.png';
                $content = file_get_contents($url);
//            dd($content);
                file_put_contents($savePath, $content);
            }

        }
    }

    public function getCarsLogo() {
        $cars = Car::all();
        for ($i=0; $i<count($cars); $i++) {
            $car = $cars[$i];
            $url = $car['logo'];
            if ($url != null && strlen($url) > 0) {
                $names = explode(".", $url);
                $extention = end($names);
//                dd($extention);
                $savePath = public_path()."/photos/cars/show/".$car['id'].'.'.$extention;
//                if ($content = file_get_contents($url))
                $content = $this->file_get_content($url);
                if ($content != null)
                    file_put_contents($savePath, $content);
            }
        }
    }

    public function getCarsBrandList() {
        return Car::all();
    }

    public function getCarsByBrand($parentid) {
        return Car::where('parentid', $parentid)->get();
    }

    function file_get_content($url) {
        if (function_exists('file_get_contents')) {
            $file_contents = @file_get_contents($url);
        }
        if ($file_contents == '') {
            $ch = curl_init();
            $timeout = 30;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;
    }

    function object_array($array) {
        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }
}
