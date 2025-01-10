<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /*
     * Displaying All Posts On Home Page
     */
    public function index(){

        $posts = Post::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return $posts;
    }

    /*
     * Uploading Post
     */
    public function create(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('posts', 'public'); // Save to storage/app/public/posts
        }
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $request->input('authId'),
            'image' => $imagePath,
            'created_at' => now()
        ]);

        return $post->id;
    }

    /*
     * Showing Post Information
     */
    public function read($id){
        $post = Post::with('user')->findOrFail($id);
        return $post;
    }

    /*
     * Updating Post In Database
     */
    public function update($id, Request $request){
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the post by ID
        $post = Post::findOrFail($id);

        if($post->user_id == $request->get('authId')){
            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($post->image && \Storage::disk('public')->exists($post->image)) {
                    \Storage::disk('public')->delete($post->image);
                }

                // Upload the new image
                $imagePath = $request->file('image')->store('posts', 'public');
                $post->image = $imagePath;
            }

            // Update the other fields
            $post->title = $request->input('title');
            $post->body = $request->input('body');

            // Save the updated post
            $post->save();

            // To the page of that post
            return $post->id;
        }else dump(403);
    }

    /*
     * Delete Post
     */
    public function delete($id, Request $request){
        $post = Post::findOrFail($id);

        // Delete the image file if it exists
        if ($post->image && \Storage::disk('public')->exists($post->image)) {
            \Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return true;
    }

    /*
     * Ajax Search
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $posts = Post::with('user')
            ->where('title', 'like', '%' . $query . '%')
            ->orWhere('body', 'like', '%' . $query . '%')
            ->orderBy('created_at', 'desc')
            ->get();

        return $posts;
    }
}
