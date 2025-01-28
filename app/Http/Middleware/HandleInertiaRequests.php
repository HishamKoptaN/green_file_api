<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        // $request->user()->givePermissionTo(['show currencies', 'create currency']);
        try {
            $settings = Setting::whereIn('name', ['site_name', 'site_logo', 'site_domain'])
                ->get()
                ->pluck('content', 'name')
                ->toArray();

            return [
                ...parent::share($request),
                'auth' => [
                    'user' => $request->user(),
                    'permissions' => $request->user()->permissions()->pluck('name')->toArray(),
                    'roles' => $request->user()->roles()->pluck('name')->toArray()
                ],
                'success' => session('success'),

                'settings' => $settings
            ];
        } catch (\Throwable $th) {
            return [
                ...parent::share($request),
                'success' => session('success'),
            ];
        }
    }
}
