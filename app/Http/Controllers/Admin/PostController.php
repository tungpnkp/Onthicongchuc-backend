<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class PostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index');
    }

    public function data()
    {
        $data = Post::select('*')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.post.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.post.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.posts.add');
    }
    public function store(Request $request)
    {
        Post::create($request->only(['title', 'content', 'status']));
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.post.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['post'] = Post::find($id);
        return view('admin.posts.update', $data);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->update($request->only(['title', 'content', 'status']));
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.post.index');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        Alert::success('Xóa thành công');
        return redirect()->route('admin.post.index');
    }
}
