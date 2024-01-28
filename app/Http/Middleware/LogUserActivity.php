<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use App\Models\Register;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Registrar la actividad del usuario en el modelo Register
        $activity = Register::create([
            'type' => 'activity',
            'description' => 'User performed an activity',
            'user_id' => Auth::id(),
        ]);
        if ($activity) {
            $comment = new Comment([
                'description' => 'Activity comment', // Descripción del comentario
            ]);
            $activity->comments()->save($comment);
        }

        return $next($request);
    }
}
