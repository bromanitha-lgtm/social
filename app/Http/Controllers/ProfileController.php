<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Get profile data for AJAX loading
     */
    public function getProfileData(User $user)
    {
        $user->loadCount(['posts', 'followers', 'followings']);
        
        $html = view('layouts.homeTabs.profile-view', [
            'profileUser' => $user
        ])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
    
    /**
     * Get current user's profile data
     */
    public function getMyProfileData()
    {
        $user = Auth::user();
        $user->loadCount(['posts', 'followers', 'followings']);
        
        $html = view('layouts.homeTabs.my-profile', [
            'user' => $user
        ])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }
}