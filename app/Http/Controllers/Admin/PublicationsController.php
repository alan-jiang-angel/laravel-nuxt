<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Publication;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 5);
        return response()->json([
            'total' => Publication::all()->count(),
            'data' => Publication::latest()->forPage($page, $limit)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $pub = Publication::create(
            $request->only(['title', 'pub_date', 'description', 'image', 'platform', 'link', 'views', 'likes', 'comments', 'favs'])
        );

        return response()->json([
            'ok' => true,
            'data' => $pub
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $pub = Publication::findOrFail($id);
        return response()->json($pub);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $pub = Publication::findOrFail($id);
        $pub->update(
            $request->only(['title', 'pub_date', 'description', 'image', 'platform', 'link', 'views', 'likes', 'comments', 'favs'])
        );

        return response()->json([
            'ok' => true,
            'data' => $pub
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $pub = Publication::findOrFail($id);
        $pub->delete();

        return response()->json([
            'ok' => true
        ]);
        //
    }
}
