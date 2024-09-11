<?php

namespace App\Http\Controllers\Api;


use App\Services\PostService;

class PostController extends BaseController
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function detail($id): \Illuminate\Http\JsonResponse
    {
        try {
            $post = $this->postService->findByField("type", $id);
            if (is_object($post)) {
                return $this->sendResponse($post, "Chi tiết bài viết");
            }
            return $this->sendError('Không tìm thấy bài viết', [], 200);

        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }
}
