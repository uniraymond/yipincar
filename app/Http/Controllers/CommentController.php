<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Comment as Comment;
use App\Article as Article;
use App\Zan as Zan;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::paginate(20);
        return view('comments/index', ['comments' => $comments]);
    }

    /**
     * Display a listing of comments from an article.
     * @param $id: articleId
     *
     * @return \Illuminate\Http\Response
     */
    public function articlecomment($id)
    {
        $comments = Comment::where('article_id', $id )->paginate(20);
        return view('comments/articlecomment', ['comments' => $comments]);
    }

    /**
     * Display a listing of zans from a comment.
     * @param $id: commentId
     *
     * @return \Illuminate\Http\Response
     */
    public function zan($id)
    {
        $zans = Zan::where('comment_id', $id )->paginate(20);
        return view('comments/zan', ['zans' => $zans, 'commentId' => $id]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function zanupdate(Request $request, $comment_id = 0)
    {
        $zanupdates = $request['published'];
        foreach ( $zanupdates as $id => $published) {
            $zan = Zan::find($id);
            $zan->comfirmed = $published ? 1 : 0;
            $zan->update();
        }
        $request->session()->flash('status', 'Published zan.');

        $zandeletes = $request['delete'];
        if ($zandeletes) {
            foreach ($zandeletes as $id => $delete) {
                if ($delete) {
                    $zan = Zan::find($id);
                    $zan->delete();
                }
            }
        }
        $request->session()->flash('status', 'Removed zan.');

        $zans = Zan::where('comment_id', $comment_id )->paginate(20);
        return view('comments/zan', ['zans' => $zans, 'commentId' => $comment_id]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function zandelete(Request $request)
    {
        foreach ($request['delete'] as $id) {
            $zan = Zan::find($id);
            $comment_id = $zan->comment_id ? $zan->comment_id : 0;
            $zan->delete();
        }

        $request->session()->flash('status', 'Removed zan.');
        $zans = Zan::where('comment_id', $comment_id )->paginate(20);

        return view('comments/zan', ['zans' => $zans, 'commentId' => $comment_id]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authuser = $request->user();
        $comment = new Comment();
        $comment->comment = $request['comment'];
        $comment->article_id = $request['article_id'];
        $comment->created_by = $authuser->id;
        $comment->save();

        return response()->Json($comment);
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
        //
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
//        $articleId = $request['$article_id'] ? $request['$article_id'] : $articleId;
        $authuser = $request->user();
        $comment = Comment::find($id);
        $comment->comment = $request['comment'];
//        $comment->article_id = $articleId;
        $comment->updated_by = $authuser->id;
        $comment->save();

        return response()->Json($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $authuser = $request->user();
        $comment = Comment::find($id);
        $comment->delete();

        return response()->Json($comment);
    }
}
