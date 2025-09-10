<?php

namespace App\Http\Controllers\Api;


use App\Imports\ExamImport;
use App\Services\ExamService;
use App\Services\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExamController extends BaseController
{
    protected $examService;
    protected $questionService;

    public function __construct(ExamService $examService, QuestionService $questionService)
    {
        $this->examService = $examService;
        $this->questionService = $questionService;
    }

    public function GetList(Request $request)
    {
        try {
            $exams = $this->examService->findByField('type', $request->get('type'));
            return $this->sendResponse($exams, "Danh sách đề thi!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function GetDetail($id): \Illuminate\Http\JsonResponse
    {
//        try {
            $exam = $this->examService->getDetail($id);
            if (is_object($exam)) {
                $questions = $this->examService->resultExam($exam);
                return $this->sendResponse($questions, "Chi tiết đề thi: $exam->title");
            }
            return $this->sendError("Đề thi không tồn tại!", [], 200);
//        } catch (\Exception $exception) {
//            return $this->sendError("Có lỗi xảy ra!", [], 200);
//        }
    }

    public
    function add(Request $request): \Illuminate\Http\JsonResponse
    {
        try {

            $data = $request->only('title', 'type');
            $exam = $this->examService->create($data);

            if ($request->hasFile('content1')) {
                Excel::import(new ExamImport($exam, 1), $request->file('content1'));
            }
            if ($request->hasFile('content2')) {
                Excel::import(new ExamImport($exam, 2), $request->file('content2'));
            }
            if ($request->hasFile('content3')) {
                Excel::import(new ExamImport($exam, 3), $request->file('content3'));
            }
            $answer = $exam->questions()->pluck('answer', 'id')->toarray();
            $answer = json_encode($answer);
            $exam = $exam->update(['answer' => $answer]);
            if ($exam) {
                return $this->sendResponse($exam, "Thêm mới thành công!");
            }
            return $this->sendError("Thất bại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public
    function result($id, Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $total = 30;
            $total_correct = $request->get('total_correct');
            $exam = $this->examService->getDetail($id);
            if (is_object($exam)) {
                $user = Auth::user();
                if ($exam->type == 1) {
                    $total = 60;
                }
                $total_wrong = $total - $total_correct;
                $percent = $total_correct / $total * 100;
                $data['total'] = $total;
                $data['total_correct'] = $total_correct . "/" . $total;
                $data['total_wrong'] = $total_wrong . "/" . $total;
                $data['percent'] = $percent . "%";
                $data = (object)$data;
                $user->exams()->syncWithoutDetaching([$exam->id => ['total_correct' => $total_correct]]);
                return $this->sendResponse($data, "Kết quả!");
            }
            return $this->sendError("Không tồn tại đề thi!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function getListByCategory($categoryId)
    {
        try {
            $exams = $this->examService->findByField("category_id", $categoryId);
            if (is_object($exams)) {
                return $this->sendResponse($exams, "Danh sách đề thi!");
            }
            return $this->sendError("Đề thi không tồn tại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }
}
