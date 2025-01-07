<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Position;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::query()
            ->select(['id', 'name'])
            ->get();

        if ($positions->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Позиции не найдены',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'positions' => $positions,
        ]);
    }
}