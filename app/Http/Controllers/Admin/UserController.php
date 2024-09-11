<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    public function data()
    {
        $data = User::select('*')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->editColumn('created_at', function ($data) {
                return $data->created_at->addYear(1)->format("d/m/Y");
            })
            ->editColumn('type', function ($data) {
                $text = "Không xác định";
                if (!empty($data->type)){
                    $type = json_decode($data->type, true);
                    $text = "|";
                    if (in_array(1, $type)) {
                        $text .= "Tiếng Anh";
                    }
                    if (in_array(2, $type)) {
                        $text .= "| KTC ngành thuế";
                    }
                    if (in_array(3, $type)) {
                        $text .= "| KTC kho bạc nhà nước";
                    }
                    if (in_array(4, $type)) {
                        $text .= "| Tất cả";
                    }
                }
                return $text;
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.user.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.user.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <a href="' . route('admin.user.get.change-password', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Change password</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.users.add');
    }

    public function store(UserRequest $request)
    {
        $type = $request->get('type');
        $type = json_encode($type);
        User::create(array_merge(['type' => $type, 'password' => Hash::make($request->get('password'))], $request->only(['name', 'code', 'phone', 'email', 'status'])));
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.user.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['item'] = User::find($id);
        return view('admin.users.update', $data);
    }

    public function getChangePpassword($id)
    {
        $data['item'] = User::find($id);
        return view('admin.users.change-password', $data);
    }

    public function update(Request $request, $id)
    {
        $post = User::find($id);
        $type = $request->get('type');
        $type = json_encode($type);
        $post->update(array_merge(['type' => $type], $request->only(['name', 'code', 'phone', 'email', 'status'])));
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.user.index');
    }

    public function postChangePpassword(Request $request, $id)
    {
        $post = User::find($id);
        $post->update(['password' => Hash::make($request->get('password'))]);
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.user.index');
    }

    public function destroy($id)
    {
        $post = User::find($id);
        $post->delete();
        Alert::success('Xóa thành công!');
        return redirect()->route('admin.user.index');
    }
}
