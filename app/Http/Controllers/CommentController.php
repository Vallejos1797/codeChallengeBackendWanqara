<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::all();
        return $this->successResponse($comments);
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            return $this->successResponse($comment);
        } else {
            return $this->errorResponse('The comment was not found.', 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'comentable_type' => 'required|string',
            'comentable_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $comment = Comment::create($validator->validated());
        return $this->successResponse($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->errorResponse('The comment was not found.', 404);
        }

        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            // Validate only relevant fields for update
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $comment->update($validator->validated());
        return $this->successResponse($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $comment->delete();
            return $this->successResponse('The comment was deleted successfully.');
        } else {
            return $this->errorResponse('The comment was not found.', 404);
        }
    }

    private function successResponse($data, $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $status);
    }

    private function errorResponse($message, $status)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    public function requestsLog(): \Illuminate\Http\JsonResponse
    {
        // Get all comments of type Register
        $comments = Comment::where('comentable_type', 'App\\Models\\Register')->get();

        // You can return the comments in the JSON response
        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }
}
