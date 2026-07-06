<?php

namespace App\Attachment\Controllers;

use App\Attachment\Requests\AttachmentUploadRequest;
use App\Attachment\Resources\AttachmentResource;
use App\Attachment\Services\AttachmentService;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Shared\Responses\ApiResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttachmentController extends Controller implements HasMiddleware
{
    public function __construct(
        private AttachmentService $service,
    ) {}

    /**
     * Upload attachment.
     */
    public function upload(
        AttachmentUploadRequest $request,
    ) {

        $attachment = $this->service->upload(
            $request->file('file')
        );

        return ApiResponse::success(

            new AttachmentResource(
                $attachment
            ),

            'Attachment uploaded successfully.',
            201

        );

    }

    /**
     * Preview attachment.
     */
    public function preview(
        Attachment $attachment,
    ) {

        return $this->service->preview(
            $attachment
        );

    }

    /**
     * Download attachment.
     */
    public function download(
        Attachment $attachment,
    ) {

        return $this->service->download(
            $attachment
        );

    }

    /**
     * Delete attachment.
     */
    public function destroy(
        Attachment $attachment,
    ) {

        $this->service->delete(
            $attachment
        );

        return ApiResponse::success(

            null,

            'Attachment deleted successfully.'

        );

    }

    /**
     * Controller Middleware.
     */
    public static function middleware(): array
    {
        return [

            new Middleware(

                'permission:attachment.upload',

                only: [
                    'upload',
                ]

            ),

            new Middleware(

                'permission:attachment.download',

                only: [
                    'preview',
                    'download',
                ]

            ),

            new Middleware(

                'permission:attachment.delete',

                only: [
                    'destroy',
                ]

            ),

        ];
    }
}