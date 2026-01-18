<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

// Jalur Webhook Xendit
Route::post('/webhooks/xendit', [WebhookController::class, 'xenditHandler']);

// Jalur Webhook Midtrans
Route::post('/webhooks/midtrans', [WebhookController::class, 'midtransHandler']);