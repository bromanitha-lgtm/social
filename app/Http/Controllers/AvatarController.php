<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AvatarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Upload avatar
     */
    public function upload(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            
            $user = Auth::user();
            
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Update user
            $user->avatar = $path;
            $user->save();
            
            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/' . $path),
                'message' => 'Avatar uploaded successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Avatar upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload avatar. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Remove avatar
     */
    public function remove()
    {
        try {
            $user = Auth::user();
            
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $user->avatar = null;
            $user->save();
            
            return response()->json([
                'success' => true,
                'avatar_url' => $user->avatar_url, // This will use gravatar
                'message' => 'Avatar removed successfully!'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Avatar remove error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove avatar.'
            ], 500);
        }
    }
}