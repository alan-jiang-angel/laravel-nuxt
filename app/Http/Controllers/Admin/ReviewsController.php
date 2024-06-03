<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Models\CustomerReview;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->get("page", 1);
        $limit = $request->get("limit", 5);
        return response()->json([
            'total' => CustomerReview::all()->count(),
            'data' => CustomerReview::latest()->forPage($page, $limit)->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $review = CustomerReview::create(
            $request->only(['review_types', 'name', 'position', 'photo', 'review'])
        );

        return response()->json([
            'ok' => true,
            'data' => $review
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerReview $review)
    {
        return $review;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $review = CustomerReview::findOrFail($id);
        $review->delete();

        return response()->json([
            'ok' => true
        ]);
    }
}
