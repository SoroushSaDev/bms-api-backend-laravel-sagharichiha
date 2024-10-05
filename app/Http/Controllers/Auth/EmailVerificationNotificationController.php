<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();
        if (!is_null($user->email_verified_at)) {
            return redirect()->intended('/dashboard');
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent']);
    }
}
