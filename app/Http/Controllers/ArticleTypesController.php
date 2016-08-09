<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\ArticleTypes;

class ArticleTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articletypes = ArticleTypes::all();
        return view('articletypes/index', ['articletypes'=>$articletypes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articletypes/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $articletype = new ArticleTypes;
        $articletype->name = $request->name;
        $articletype->description = $request->description;
        $articletype->save();

        $request->session()->flash('status', 'articletypes: '. $articletype->name .' has been created Successful!');

        return redirect('admin/articletypes');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $articletype = ArticleTypes::find($id);

        return view('articletypes/edit', ['articletype'=>$articletype]);
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
        $articletype = ArticleTypes::find($id);
        if ($request->name) {
            $articletype->name = $request->name;
        }
        if ($request->description) {
            $articletype->description = $request->description;
        }
        $articletype->save();

        $request->session()->flash('status', 'Update Successful!');

        return redirect('admin/articletypes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $articletype = ArticleTypes::find($id);
        $articletype->delete();

        $request->session()->flash('status', 'Delete Successful!');
        return redirect('admin/articletypes');
    }
}
