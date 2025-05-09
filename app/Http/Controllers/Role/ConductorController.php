<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View as View;

class ConductorController extends Controller
{
    /**
     * Muestra el dashboard del conductor.
     */
    public function index(): View
    {
        return view('dashboard.conductor');
    }
}