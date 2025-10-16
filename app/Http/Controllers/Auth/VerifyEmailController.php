<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AspiranteService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    protected $aspiranteService;

    public function __construct(AspiranteService $aspiranteService)
    {
        $this->aspiranteService = $aspiranteService;
    }
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();

            $userLogged = event(new Verified($user));

            // ***** Registrar como aspirante
            $this->aspiranteService->createFromUser($user);
        }

        return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
    }
}
