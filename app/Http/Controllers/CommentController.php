<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:1|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();
        
        if ($request->has('parent_id') && $request->parent_id) {
            $comment->parent_id = $request->parent_id;
        }
        
        $comment->save();
        
        // Increment post comments count
        $post->incrementCommentsCount();
        
        // Load user relationship
        $comment->load('user');
        
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => $comment,
            'comments_count' => $post->fresh()->comments_count,
            'html' => view('test.posts.partials.comment', compact('comment'))->render()
        ]);
    }
    
    /**
     * Update a comment.
     */
    public function update(Request $request, Comment $comment)
    {
        // Authorization
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:1|max:1000'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $comment->content = $request->content;
        $comment->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'comment' => $comment->fresh()
        ]);
    }
    
    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment)
    {
        // Authorization
        if ($comment->user_id !== Auth::id() && !Auth::user()->can('delete-comments')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        
        $post = $comment->post;
        
        // Recursively delete replies
        $this->deleteCommentWithReplies($comment);
        
        // Decrement post comments count
        $post->decrementCommentsCount();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully',
            'comments_count' => $post->fresh()->comments_count
        ]);
    }
    
    /**
     * Recursively delete comment and its replies.
     */
    private function deleteCommentWithReplies(Comment $comment): void
    {
        // Delete all replies first
        foreach ($comment->replies as $reply) {
            $this->deleteCommentWithReplies($reply);
        }
        
        // Delete the comment
        $comment->delete();
    }
    
    /**
     * Get comments for a post (with pagination).
     */
    public function getComments(Post $post)
    {
        $comments = $post->comments()
            ->withCount('replies')
            ->latest()
            ->paginate(10);
            
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('test.posts.partials.comments', compact('comments'))->render(),
                'has_more' => $comments->hasMorePages()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
    
    /**
     * Get replies for a comment.
     */
    public function getReplies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->latest()
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'html' => view('test.posts.partials.replies', compact('replies'))->render(),
            'has_more' => $replies->hasMorePages()
        ]);
    }
}