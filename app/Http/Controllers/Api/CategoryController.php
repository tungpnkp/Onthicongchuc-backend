<?php

namespace App\Http\Controllers\Api;

use App\Entities\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends BaseController
{
    public function index()
    {
        try {
            $data = Category::query()
                ->with("children")
                ->select(['id', 'name', 'sub_no'])
                ->where('status', '=',CATEGORY_STATUS_ACTIVE)
                ->where('parent_id', null)
                ->orderBy('sub_no')->get();

            return $this->sendResponse($data, "Danh sách module!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }
}
