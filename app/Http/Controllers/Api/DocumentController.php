<?php

namespace App\Http\Controllers\Api;


use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends BaseController
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function GetList(Request $request)
    {
        try {
            $documents = $this->documentService->findByField('type', $request->get('type'));
            return $this->sendResponse($documents, "Danh sách tài liệu!");
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function GetDetail($id): \Illuminate\Http\JsonResponse
    {
        try {
            $document = $this->documentService->getDetail($id);
            if (is_object($document)) {
                return $this->sendResponse($document, "Chi tiết tài liệu!");
            }
            return $this->sendError("Tài liệu không tồn tại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function add(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->only('title', 'type');
            if ($request->hasFile('content')) {

                $imageName = time() . '.' . $request->file('content')->getClientOriginalExtension();
                $request->file('content')->move(public_path('documents'), $imageName);
                $data = array_merge($data, ['content' => config("app.url") . "/documents/" . $imageName]);
//
//            $path = $request->file('content')->store('public/documents');
//            $data = array_merge($data, ['content' => str_replace("public/", "storage/", $path)]);
            }
            $document = $this->documentService->create($data);
            if (is_object($document)) {
                return $this->sendResponse($document, "Thêm mới thành công!");
            }
            return $this->sendError("Thát bại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }

    public function getListByCategory($categoryId)
    {
        try {
            $document = $this->documentService->findByField("category_id", $categoryId);
            if (is_object($document)) {
                return $this->sendResponse($document, "Chi tiết tài liệu!");
            }
            return $this->sendError("Tài liệu không tồn tại!", [], 200);
        } catch (\Exception $exception) {
            return $this->sendError("Có lỗi xảy ra!", [], 200);
        }
    }
}
