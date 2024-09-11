<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\SettingRequest;
use App\Imports\ExamImport;
use App\Services\PostService;
use App\Services\SettingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        try {
            $settings = $this->settingService->all();
            if (is_object($settings)) {
                return $this->sendResponse($settings, "Chi tiết bài viết");
            }
            return $this->sendError('Không tìm thấy bài viết', [], 200);

        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function store(SettingRequest $request)
    {
        try {
            $data = $request->only('title', 'value', 'type');
            $setting = $this->settingService->create($data);

            if ($setting) {
                return $this->sendResponse($setting, "Thêm mới cài đặt thành công!");
            }
            return $this->sendError("Thất bại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function update(SettingRequest $request, $id)
    {
        try {
            $data = $request->only('title', 'value', 'type');
            $setting = $this->settingService->update($data['id'], $id);

            if ($setting) {
                return $this->sendResponse($setting, "Cập nhật cài đặt thành công!");
            }
            return $this->sendError("Thất bại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $this->settingService->delete($id);
            return $this->sendResponse([], "Xóa dữ liệu thành công!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

}
