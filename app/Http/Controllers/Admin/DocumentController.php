<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Category;
use App\Entities\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class DocumentController extends Controller
{
    public function index()
    {
        return view('admin.documents.index');
    }

    public function data()
    {
        $data = Document::select('*')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->editColumn('type', function ($data) {
                if ($data->type == 3) {
                    return "Kiến thức chung kho bạc nhà nước";
                } elseif ($data->type == 1) {
                    return "Kiến thức chung ngành thuế";
                }
                return "Tiếng anh";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.document.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.document.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $category = Category::query()->doesntHave('children')->get();
        return view('admin.documents.add', ['categories' => $category]);
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'type', 'status');
        if ($request->hasFile('content')) {
            $imageName = time() . '.' . $request->file('content')->getClientOriginalExtension();
            $request->file('content')->move(public_path('documents'), $imageName);
            $data = array_merge($data, ['content' => config("app.url") . "/documents/" . $imageName]);
        }
        Document::create($data);
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.document.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['item'] = Document::find($id);
        $data['categories'] = Category::query()->doesntHave('children')->get();
        return view('admin.documents.update', $data);
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
