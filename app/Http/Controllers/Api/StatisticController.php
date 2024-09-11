<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StatisticController extends BaseController
{
    public function GetList()
    {
        try {
            $user = Auth::user();
            $statistics = $user->exams;
            return $this->sendResponse($statistics, "Danh sách đề thi!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function GetDetail($exam_id)
    {
        try {
            $user = Auth::user();
            $statistic = $user->exams()->wherePivot('exam_id', $exam_id)->first();
            $data = [];
            if (is_object($statistic)) {
                $total_correct = $statistic->pivot->total_correct;
                $total = 30;
                if ($statistic->type == 1) {
                    $total = 60;
                }
                $total_wrong = $total - $total_correct;
                $percent = $total_correct / $total * 100;
                $data['total'] = $total;
                $data['total_correct'] = $total_correct . "/" . $total;
                $data['total_wrong'] = $total_wrong . "/" . $total;
                $data['percent'] = $percent . "%";
            }
            $data = (object)$data;
            return $this->sendResponse($data, "Kết quả đề thi!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }

    }
}
