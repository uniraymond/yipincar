<?php

namespace App\Http\Controllers;

use App\Taboo;
use Illuminate\Http\Request;

use App\Http\Requests;

class TabooController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taboos = Taboo::paginate(10);
        $tabooCategories = Taboo::groupby('category')->distinct()->get();
        return view('taboo/index', ['taboos' => $taboos, 'categories' => $tabooCategories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taboo = new Taboo();
        $tabooCategories = Taboo::groupby('category')->distinct()->get();
        return view('taboo/create', ['categories' => $tabooCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|unique',
        ]);

        $taboo = new Taboo();
        $taboo->content = $request['content'];
        $taboo->category = $request['category'];
        $taboo->save();

        $request->session()->flash('status', '敏感字添加成功..');
        return view('taboo/show', ['taboo'=>$taboo]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $taboo = Taboo::findorFail($id);

        return view('taboo/show', ['taboo'=>$taboo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $taboo = Taboo::findorFail($id);

        return view('taboo/edit', ['taboo'=>$taboo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $taboo = Taboo::findorFail($id);

        $this->validate($request, [
            'content' => 'required|unique',
        ]);

        $taboo->content = $request['content'];
        $taboo->category = $request['category'];
        $taboo->save();

        $request->session()->flash('status', '敏感字修改成功.');
        return view('taboo/show', ['taboo'=>$taboo]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $taboo = Taboo::findorFail($id);
        $taboo->delete();

        $request->session()->flash('status', '敏感字修改成功.');
        return view('taboo/index');
    }
}
