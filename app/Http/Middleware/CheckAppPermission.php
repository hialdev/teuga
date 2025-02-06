<?php

namespace App\Http\Middleware;

use App\Models\Application;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class CheckAppPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil APP_CODE dari .env
        $appCode = env('APP_CODE', 'OSN');

        // Cek apakah APP_CODE ada di database
        $application = Application::where('code', $appCode)->first();
        if (!$application) {
            abort(403, 'Application not found.');
        }

        // Periksa apakah user sudah login
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = Auth::user(); // Gunakan langsung Auth::user()

        // Pastikan user memiliki role sebelum mengakses roles[0]
        if ($user->roles->isEmpty()) {
            abort(403, 'No role assigned.');
        }

        $permissionArray = $user->roles[0]->permissions->pluck('name')->toArray();

        // Periksa apakah user memiliki permission sesuai APP_CODE
        if (!in_array($application->name, $permissionArray)) {
            abort(403, 'You do not have permission to access this application.');
        }

        return $next($request);
    }
}
