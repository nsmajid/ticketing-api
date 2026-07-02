<?php

use App\Application\Controllers\ApplicationController;
use App\ApplicationFeature\Controllers\ApplicationFeatureController;
use App\Auth\Controllers\AuthController;
use App\Sla\Controllers\SlaRuleController;
use App\Ticket\Controllers\TicketController;
use App\TicketCategory\Controllers\TicketCategoryController;
use App\TicketPriority\Controllers\TicketPriorityController;
use App\TicketStatus\Controllers\TicketStatusController;
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
    Route::apiResource('tickets', TicketController::class);

    Route::prefix('tickets')->group(function () {
        Route::put(
            '{ticket}/submit',
            [TicketController::class, 'submit']
        );

        Route::put(
            '{ticket}/review',
            [TicketController::class, 'review']
        );
    });
});
