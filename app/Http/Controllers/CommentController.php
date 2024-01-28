<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

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
        $request->validate([
            'description' => 'required|string',
            'comentable_type' => 'required|string',
            'comentable_id' => 'required|integer'
        ]);

        $comment = Comment::create($request->all());
        return $this->successResponse($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if ($comment) {
            $request->validate([
                'description' => 'required|string',
                // Validar solo los campos relevantes para la actualización
            ]);

            $comment->update($request->all());
            return $this->successResponse($comment);
        } else {
            return $this->errorResponse('The comment was not found.', 404);
        }
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
}
