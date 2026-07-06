<?php

use App\Application\Controllers\ApplicationController;
use App\ApplicationFeature\Controllers\ApplicationFeatureController;
use App\Attachment\Controllers\AttachmentController;
use App\Auth\Controllers\AuthController;
use App\Sla\Controllers\SlaRuleController;
use App\Ticket\Controllers\TicketController;
use App\TicketCategory\Controllers\TicketCategoryController;
use App\TicketComment\Controllers\TicketCommentController;
use App\TicketPriority\Controllers\TicketPriorityController;
use App\TicketStatus\Controllers\TicketStatusController;
use App\TicketWaitingFor\Controllers\TicketWaitingForController;
use App\User\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::apiResource('users', UserController::class)->except(['destroy']);

    Route::apiResource('ticket-categories', TicketCategoryController::class);
    Route::apiResource('ticket-priorities', TicketPriorityController::class);
    Route::apiResource('ticket-statuses', TicketStatusController::class);
    Route::apiResource('sla-rules', SlaRuleController::class);
    Route::apiResource('applications', ApplicationController::class);
    Route::apiResource('application-features', ApplicationFeatureController::class);
    Route::apiResource('ticket-waiting-fors', TicketWaitingForController::class);
    Route::apiResource('tickets', TicketController::class);

    Route::prefix('tickets')->group(function () {
        Route::put('{ticket}/submit', [TicketController::class, 'submit']);
        Route::put('{ticket}/review', [TicketController::class, 'review']);
        Route::post('{ticket}/assignments', [TicketController::class, 'assign']);
        Route::put('{ticket}/start-progress', [TicketController::class, 'startProgress']);
        Route::put('{ticket}/pending', [TicketController::class, 'pending']);
        Route::put('{ticket}/resume', [TicketController::class, 'resume']);
        Route::put('{ticket}/resolve', [TicketController::class, 'resolve']);
        Route::put('{ticket}/accept', [TicketController::class, 'accept']);
        Route::get('{ticket}/timeline', [TicketController::class, 'timeline']);

        Route::get('/{ticket}/comments', [TicketCommentController::class, 'index']);
        Route::post('/{ticket}/comments', [TicketCommentController::class, 'store']);
        Route::get('/{ticket}/comments/{comment}', [TicketCommentController::class, 'show']);
        Route::put('/{ticket}/comments/{comment}', [TicketCommentController::class, 'update']);
        Route::delete('/{ticket}/comments/{comment}', [TicketCommentController::class, 'destroy']);
    });


    Route::prefix('attachments')
        ->group(function () {
            Route::post('/', [AttachmentController::class, 'upload'])->name('attachments.upload');
            Route::get('/{attachment}/preview', [AttachmentController::class, 'preview'])->name('attachments.preview');
            Route::get('/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
            Route::delete('/{attachment}', [AttachmentController::class, 'destroy']);
        });
});
