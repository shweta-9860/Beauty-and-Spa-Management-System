<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // User login केलेला नसेल तर पुढे जाऊ देऊ नका
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Branch User
        |--------------------------------------------------------------------------
        |
        | branch_id असलेला user हा branch-specific user आहे.
        |
        */
        if (!empty($user->branch_id)) {

            /*
            | Route मध्ये {branch} parameter असेल तर
            | user फक्त स्वतःच्या branch ला access करू शकतो.
            */
            $branch = $request->route('branch');

            if ($branch) {

                // Route Model Binding मुळे Branch object मिळू शकतो
                $branchId = is_object($branch)
                    ? $branch->id
                    : $branch;

                if ((int) $branchId !== (int) $user->branch_id) {
                    abort(403, 'You do not have access to this branch.');
                }
            }
        }

        return $next($request);
    }
}