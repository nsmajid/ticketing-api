<?php

namespace App\Attachment\Services;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentService
{
    /**
     * Upload attachment.
     */
    public function upload(
        UploadedFile $file
    ): Attachment {

        return DB::transaction(function () use ($file) {

            $attachment = new Attachment();

            $directory = $this->generateDirectory();
            $ulid = (string) Str::ulid();

            $filename = $this->generateFilename(
                $ulid,
                $file
            );

            // $path = $this->fullPath(
            //     $directory,
            //     $filename
            // );

            // $stored = Storage::disk(config('attachment.disk'))
            //     ->putFileAs(
            //         $directory,
            //         $file,
            //         $filename
            //     );

            $path = Storage::disk(config('attachment.disk'))
                ->putFileAs(
                    $directory,
                    $file,
                    $filename,
                    [
                        'visibility' => 'private',
                    ]
                );

            throw_if(
                $path === false,
                \Exception::class,
                'Failed to upload attachment.'
            );

            // Storage::disk(
            //     config('attachment.disk')
            // )->putFileAs(
            //     $directory,
            //     $file,
            //     $filename
            // );

            $attachment->fill([

                'disk' => config('attachment.disk'),


                'directory' => $directory,

                'path' => $path,

                'filename' => $filename,

                'ulid' => $ulid,

                'original_filename' => $file->getClientOriginalName(),

                'extension' => strtolower(
                    $file->getClientOriginalExtension()
                ),

                'mime_type' => $file->getMimeType(),

                'size' => $file->getSize(),

                'checksum' => hash_file(
                    'sha256',
                    $file->getRealPath()
                ),

                'uploaded_by' => auth()->id(),

                'is_temporary' => true,

                'expired_at' => now()->addHours(
                    intval(config('attachment.temporary_hours'))
                ),

            ]);

            $attachment->save();

            return $attachment;
        });
    }

    /**
     * Preview attachment.
     */
    public function preview(
        Attachment $attachment
    ): StreamedResponse {

        return Storage::disk(
            config('attachment.disk')
        )->response(
            $attachment->path
        );
    }

    /**
     * Download attachment.
     */
    public function download(
        Attachment $attachment
    ): StreamedResponse {

        return Storage::disk(
            config('attachment.disk')
        )->download(
            $attachment->path,
            $attachment->original_filename
        );
    }

    /**
     * Delete attachment.
     */
    public function delete(
        Attachment $attachment
    ): void {

        DB::transaction(function () use ($attachment) {

            /**
             * Step 21
             * Cek attachment usages.
             */

            Storage::disk(
                 config('attachment.disk')
            )->delete(
                $attachment->path
            );

            $attachment->delete();
        });
    }

    /**
     * Cleanup expired temporary attachments.
     */
    public function cleanupTemporary(): int
    {

        $attachments = Attachment::query()

            ->where(
                'is_temporary',
                true
            )

            ->whereNotNull(
                'expired_at'
            )

            ->where(
                'expired_at',
                '<',
                now()
            )

            ->get();

        foreach ($attachments as $attachment) {

            Storage::disk(
                 config('attachment.disk')
            )->delete(
                $attachment->path
            );

            $attachment->delete();
        }

        return $attachments->count();
    }

    /**
     * Generate directory.
     */
    private function generateDirectory(): string
    {

        return sprintf(

            'attachments/%s/%s',

            now()->format('Y'),

            now()->format('m')

        );
    }

    /**
     * Generate filename.
     */
    private function generateFilename(
        string $ulid,
        UploadedFile $file
    ): string {

        return sprintf(

            '%s.%s',

            $ulid,

            strtolower(
                $file->getClientOriginalExtension()
            )

        );
    }

    /**
     * Build full path.
     */
    private function fullPath(
        string $directory,
        string $filename
    ): string {

        return sprintf(

            '%s/%s',

            $directory,

            $filename

        );
    }
}
