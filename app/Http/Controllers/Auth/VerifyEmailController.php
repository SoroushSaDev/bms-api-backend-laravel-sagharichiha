<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(
                config('app.frontend_url').'/dashboard?verified=1'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(
            config('app.frontend_url').'/dashboard?verified=1'
        );
    }

    public function manual(User $user, $hash): RedirectResponse
    {
        if (!hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
            return redirect()->intended(
                config('app.frontend_url').'/dashboard?verified=0'
            );
        }
        if (!$user->hasVerifiedEmail()) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }
        return redirect()->intended(
            config('app.frontend_url').'/dashboard?verified=1'
        );
    }
}
