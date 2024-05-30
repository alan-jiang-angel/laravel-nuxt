<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Subscription;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Subscription::paginate(10)); 
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
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        return response()->json($sub);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $sub = Subscription::findOrFail($id);
        $sub->update(
            $request->only(['title', 'pub_date', 'description', 'image', 'platform', 'link', 'views', 'likes', 'comments', 'favs'])
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
        //
    }
}
