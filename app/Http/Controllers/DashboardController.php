<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;

class DashboardController extends Controller
{
    public function index(Request $request){
        switch (Auth::user()->role) {
            case 'admin':
                # Admin Dashboard
                $view = 'dashboard.admin';
                break;

            case 'user':
                # User Dashboard
                $view = 'dashboard.user';
                break;    
            
            case 'customer':
                # Customer Dashboard
                $view = 'dashboard.customer';
                break;    
            
            default:
                # code...
                break;
        }
        return view($view);
    }
}
