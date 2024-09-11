<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index');
    }

    public function data()
    {
        $data = Notification::select('*')->orderBy('id', 'desc');
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.notification.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.notification.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.notifications.add');
    }

    public function store(Request $request)
    {
        $notification = Notification::create($request->only(['title', 'content', 'status']));
        $notification->users()->attach([$notification->id]);
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.notification.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data['post'] = Notification::find($id);
        return view('admin.notifications.update', $data);
    }

    public function update(Request $request, $id)
    {
        $post = Notification::find($id);
        $post->update($request->only(['title', 'content', 'status']));
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.notification.index');
    }

    public function destroy($id)
    {
        $post = Notification::find($id);
        $post->delete();
        Alert::success('Xóa thành công');
        return redirect()->route('admin.notification.index');
    }
}
