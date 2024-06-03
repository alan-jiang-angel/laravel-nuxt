<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Subscription;
use App\Http\Controllers\Controller;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 5);
        return response()->json([
            'total' => Subscription::all()->count(),
            'data' => Subscription::latest()->forPage($page, $limit)->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        return response()->json($sub);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $sub = Subscription::create(
            $request->only(['email', 'description'])
        );

        return response()->json([
            'ok' => true,
            'data' => $sub
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        $sub->update(
            $request->only(['email', 'description'])
        );

        return response()->json([
            'ok' => true,
            'data' => $sub
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        $sub->delete();

        return response()->json([
            'ok' => true
        ]);
    }

    public function deleteItems(Request $request): JsonResponse
    {
        $items = $request->get('items');
        Subscription::destroy($items);
        return response()->json([
            'ok' => true,
            'data' => $items
        ]);
    }
}
