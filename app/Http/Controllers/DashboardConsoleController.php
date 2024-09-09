<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardConsoleController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();

        return Inertia::render('Console/Dashboard', [
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'totalPermissions' => $totalPermissions,
        ]);
    }

}
