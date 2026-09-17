<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class RopaAdminController extends Controller
{
    /**
     * Display the official Record of Processing Activities (ROPA / Registre des activités de traitement).
     * Compliant with GDPR Article 30 and CNIL guidelines.
     */
    public function index(): View
    {
        $controller = config('ropa.controller', []);
        $activities = config('ropa.activities', []);

        return view('admin.ropa.index', compact('controller', 'activities'));
    }
}
