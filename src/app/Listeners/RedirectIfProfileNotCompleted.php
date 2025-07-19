<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;

class RedirectIfProfileNotCompleted
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (!$user->profile_completed) {
            // セッションにフラグを入れて後でリダイレクトに使う
            Session::put('redirect_to_profile', true);
        }
    }
}