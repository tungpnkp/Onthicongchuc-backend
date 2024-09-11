<?php

namespace App\Http\Controllers\Admin;

use App\Entities\Category;
use App\Entities\Exam;
use App\Imports\ExamImport;
use App\Services\ExamService;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ExamController extends Controller
{
    protected $examService;
    protected $questionService;

    public function __construct(ExamService $examService, QuestionService $questionService)
    {
        $this->examService = $examService;
        $this->questionService = $questionService;
    }

    public function index()
    {
        return view('admin.exams.index');
    }

    public function data()
    {
        $data = Exam::select('*')->orderBy('id', 'desc');
        $categories = Category::query()->doesntHave('children')->get();
        return DataTables::of($data)
            ->editColumn('status', function ($data) {
                return $data->status == 1 ? "Hoạt động" : "Tạm dừng";
            })
            ->editColumn('type', function ($data) use ($categories) {
                foreach ($categories as $category) {
                    if ($data->type == $category->id) {
                        return $category->name;
                    }
                }
                return "Kiến thức chung";
            })
            ->addColumn('action', function ($data) {
                return '
                        <form action="' . route('admin.exam.destroy', $data->id) . '" method="post">' .
                    method_field('DELETE') . csrf_field() . '
                            <a href="' . route('admin.exam.edit', $data->id) . '" class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Edit</a>
                            <button type="submit" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-times"></i> Delete</button>
                        </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $category = Category::query()->doesntHave('children')->get();
        return view('admin.exams.add', ['categories' => $category]);
    }

    protected function uploadFile($file, $number): string
    {
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . "_" . time() . "_$number";
        $ext = $file->getClientOriginalExtension();
        $file_name = $name . '.' . $ext;
        $file->move(public_path('exams'), $file_name);
        return "exams/" . $file_name;
    }

    public function store(Request $request)
    {
        $data = $request->only('title', 'type', 'status');
        $exam = $this->examService->create($data);
        $files = [];
//        Đề 1
        if ($request->hasFile('content1')) {
            $file1 = $request->file('content1');
            Excel::import(new ExamImport($exam, 1), $file1);
            $files[] = $this->uploadFile($file1, 1);
        } else {
            $files[] = null;
        }
//        Đề 2
        if ($request->hasFile('content2')) {
            $file2 = $request->file('content2');
            Excel::import(new ExamImport($exam, 2), $file2);
            $files[] = $this->uploadFile($file2, 2);
        } else {
            $files[] = null;
        }
//        Đề 3
        if ($request->hasFile('content3')) {
            $file3 = $request->file('content3');
            Excel::import(new ExamImport($exam, 3), $file3);
            $files[] = $this->uploadFile($file3, 3);
        } else {
            $files[] = null;
        }
//        Đề 4
        if ($request->hasFile('content4')) {
            $file4 = $request->file('content4');
            Excel::import(new ExamImport($exam, 4), $file4);
            $files[] = $this->uploadFile($file4, 4);
        } else {
            $files[] = null;
        }
        $files = json_encode($files);
        $exam->update(['files' => $files]);
        $answer = $exam->questions()->pluck('answer', 'id')->toarray();
        $answer = json_encode($answer);
        $exam->update(['answer' => $answer]);
        Alert::success('Thêm mới thành công');
        return redirect()->route('admin.exam.index');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $item = $this->examService->find($id);
        $data['files'] = json_decode($item->files, true);
        $data['item'] = $item;
        $data['categories'] = Category::query()->doesntHave('children')->get();
        return view('admin.exams.update', $data);
    }

    public function update(Request $request, $id)
    {
        $item = $this->examService->find($id);
        $item->update($request->only('title', 'type','status'));
        $files = json_decode($item->files, true);
//        Đề 1
        if ($request->hasFile('content1')) {
            $item->questions()->where('type', 1)->delete();
            if (!empty($files[0])) {
                if (file_exists($files[0])) {
                    unlink($files[0]);
                }
            }
            $file1 = $request->file('content1');
            Excel::import(new ExamImport($item, 1), $file1);
            $files[0] = $this->uploadFile($file1, 1);
        }
//        Đề 2
        if ($request->hasFile('content2')) {
            $item->questions()->where('type', 2)->delete();
            if (!empty($files[1])) {
                if (file_exists($files[1])) {
                    unlink($files[1]);
                }
            }
            $file2 = $request->file('content2');
            Excel::import(new ExamImport($item, 2), $file2);
            $files[1] = $this->uploadFile($file2, 2);
        }
//        Đề 3
        if ($request->hasFile('content3')) {
            $item->questions()->where('type', 3)->delete();
            if (!empty($files[2])) {
                if (file_exists($files[2])) {
                    unlink($files[2]);
                }
            }
            $file3 = $request->file('content3');
            Excel::import(new ExamImport($item, 3), $file3);
            $files[2] = $this->uploadFile($file3, 3);
        }
//        Đề 4
        if ($request->hasFile('content4')) {
            $item->questions()->where('type', 4)->delete();
            if (!empty($files[3])) {
                if (file_exists($files[3])) {
                    unlink($files[3]);
                }
            }
            $file4 = $request->file('content4');
            Excel::import(new ExamImport($item, 4), $file4);
            $files[3] = $this->uploadFile($file4, 4);
        }
        $files = json_encode($files);
        $item->update(['files' => $files]);
        Alert::success('Cập nhật thành công');
        return redirect()->route('admin.exam.index');
    }

    public function destroy($id)
    {
        $item = $this->examService->find($id);
        $files = json_decode($item->files, true);
        if (!empty($files)) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
        $item->delete();
        Alert::success('Xóa thành công');
        return redirect()->route('admin.exam.index');
    }
}
