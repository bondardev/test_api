<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ImageProcessingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'count' => 'integer|min:1|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'fails' => $validator->errors(),
            ], 400);
        }


        $count = $request->input('count', 5);

        $users = User::query()
            ->orderBy('id', 'asc')
            ->paginate($count);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No users found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $users->perPage(),
            'links' => [
                'next_url' => $users->nextPageUrl(),
                'prev_url' => $users->previousPageUrl(),
            ],
            'users' => $users->items(),
        ], 200);
    }


    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid user ID',
                'fails' => $validator->errors(),
            ], 400);
        }

        $user = User::with('position')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'position_id' => $user->position->id ?? null,
                'position' => $user->position->name ?? null,
                'photo' => $user->photo,
            ],
        ], 200);
    }



    public function store(Request $request, ImageProcessingService $imageService)
    {

        $token = $request->header('Token');

        if (!$token || !Cache::pull($token)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|max:100|unique:users',
            'phone' => 'required|regex:/^\+380\d{9}$/|unique:users',
            'position_id' => 'required|exists:positions,id',
            'photo' => 'required|image|mimes:jpeg,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'fails' => $validator->errors(),
            ], 422);
        }

        if (User::where('email', $request->email)->exists() || User::where('phone', $request->phone)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User with this phone or email already exists.',
            ], 409);
        }

        $optimizedPhotoUrl = $imageService->process($request->file('photo'), 'photos');

        if (!$optimizedPhotoUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Image processing failed.',
            ], 500);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position_id' => $request->position_id,
            'photo' => $optimizedPhotoUrl,
        ]);

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'message' => 'New user successfully registered',
        ], 201);
    }
}
