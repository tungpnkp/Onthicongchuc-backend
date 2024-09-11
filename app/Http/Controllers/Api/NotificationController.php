<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;

class NotificationController extends BaseController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function GetList()
    {
        try {
            $notifications = $this->notificationService->getNotificationsOfUser();
            return $this->sendResponse($notifications, "Danh sách thông báo của bạn!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function GetDetail($id): \Illuminate\Http\JsonResponse
    {
        try {
            $notification = $this->notificationService->getFirstNotificationOfUser($id);
            if (is_object($notification)) {
                return $this->sendResponse($notification, "Chi tiết thông báo!");
            }
            return $this->sendError("Thông báo không tồn tại", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function ChangeStatus($id): \Illuminate\Http\JsonResponse
    {
        try {
            $notification = $this->notificationService->UpdateStatusPivot($id);
            if ($notification) {
                return $this->sendResponse($notification, "Đã xem!");
            }
            return $this->sendError("Thông báo này đã được xem rồi!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }
}
