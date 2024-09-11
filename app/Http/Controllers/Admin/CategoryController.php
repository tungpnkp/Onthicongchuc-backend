<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Category;
use App\Entities\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.category.index');
    }

    public function data()
    {
        $data = Category::select('*')->orderBy('id', 'asc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.category.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.category.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.category.add', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'parent_id', 'status');
        $maxSubNo = Category::max('sub_no');
        $subNo = !$maxSubNo ? '1' : $maxSubNo + 1;

        $saved = array(
            'name' => $data['name'],
            'parent_id' => $data['parent_id'] == 0 ? null : $data['parent_id'],
            'status' => $data['status'],
            'sub_no' => $subNo
        );

        Category::create($saved);
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.category.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['item'] = Category::find($id);
        $data['categories'] = Category::all();
        return view('admin.category.update', $data);
    }

    public function update(Request $request, $id)
    {
        $document = Document::find($id);
        $data = $request->only('title', 'type', 'status');
        if ($request->hasFile('content')) {
            unlink($document->content);
            $imageName = time() . '.' . $request->file('content')->getClientOriginalExtension();
            $request->file('content')->move(public_path('documents'), $imageName);
            $data = array_merge($data, ['content' => config("app.url") . "/documents/" . $imageName]);
        }
        $document->update($data);
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.document.index');
    }

    public function destroy($id)
    {
        $document = Document::find($id);
        $document->delete();
        Alert::success('Xóa thành công');
        return redirect()->route('admin.document.index');
    }
}
