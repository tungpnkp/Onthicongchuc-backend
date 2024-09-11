<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.admins.index');
    }
    public function data()
    {
        $data = Admin::select('*')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->type == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.account.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.account.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <a href="' . route('admin.account.get.change-password', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Change password</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admins.add');
    }

    public function store(Request $request)
    {
        Admin::create(array_merge(['password' => Hash::make($request->get('password'))], $request->only(['name', 'email', 'status'])));
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.account.index');
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

    public function edit($id)
    {
        $data['item'] = Admin::find($id);
        return view('admin.admins.update', $data);
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::find($id);
        $admin->update($request->only(['name', 'email', 'status']));
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.account.index');
    }

    public function getChangePpassword($id)
    {
        $data['item'] = Admin::find($id);
        return view('admin.admins.change-password', $data);
    }
    public function postChangePpassword(Request $request, $id)
    {
        $post = Admin::find($id);
        $post->update(['password' => Hash::make($request->get('password'))]);
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.account.index');
    }

    public function destroy($id)
    {
        $post = Admin::find($id);
        $post->delete();
        Alert::success('Xóa thành công!');
        return redirect()->route('admin.account.index');
    }
}
