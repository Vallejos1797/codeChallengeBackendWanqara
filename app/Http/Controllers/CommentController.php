<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    // Method to retrieve all comments
    public function index()
    {
        $comments = Comment::all();
        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    // Method to retrieve a specific comment by its ID
    public function show($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            return response()->json([
                'success' => true,
                'data' => $comment,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The comment was not found.',
            ], 404);
        }
    }

    // Method to store a new comment
    public function store(Request $request)
    {
        // Validate required fields
        $request->validate([
            'description' => 'required|string',
            'comentable_type' => 'required|string',
            'comentable_id' => 'required|integer'
        ]);


        // Create the comment using the provided data
        $comment = Comment::create(['description' => $request->input('description'),
            'comentable_type' => $request->input('comentable_type'),
            'comentable_id' => $request->input('comentable_id'),]);

        return response()->json([
            'success' => true,
            'data' => $comment,
        ], 201);
    }

    // Method to update an existing comment
    public function update(Request $request, $id)
    {
        // Find the comment by its ID
        $comment = Comment::find($id);
        if ($comment) {
            // Validate required fields
            $request->validate([
                'description' => 'required|string',
                'weather_id' => 'required',
                'register_id' => 'required'
            ]);

            // Update the comment with the provided data
            $comment->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $comment,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The comment was not found.',
            ], 404);
        }
    }

    // Method to delete an existing comment
    public function destroy($id)
    {
        // Find the comment by its ID
        $comment = Comment::find($id);
        if ($comment) {
            // Delete the found comment
            $comment->delete();
            return response()->json([
                'success' => true,
                'message' => 'The comment was deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The comment was not found.',
            ], 404);
        }
    }
}
