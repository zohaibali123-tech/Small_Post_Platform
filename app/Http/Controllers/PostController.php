<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $posts = Post::latest()->paginate(3);
            return response()->json($posts);
        }

        return view('posts');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
    
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id()
        ]);
    
        return response()->json([
            'success' => 'Post Added Successfully!...',
            'post' => $post
        ]);   
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        $post = Post::find($id);
        
        if (!$post) {
            return response()->json(['error' => 'Post Not Found!...'], 404);
        }

        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function search(Request $request) {
        $query = $request->input('search');
        
        if (!$query) {
            return response()->json(['data' => []]);
        }
    
        $posts = Post::where('title', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->get();
    
        return response()->json(['data' => $posts]);
    }    


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return response()->json(['success' => 'Post Updated Successfully!...']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Post::destroy($id);
        return response()->json(['success' => 'Post Deleted Successfully!...']);
    }

    public function userPostsPage()
    {
        return view('userposts'); // ✅ اب یہ صحیح طریقے سے کھلے گا
    }

    public function getUserPosts(Request $request)
    { 
        $user = Auth::user(); // لاگ ان شدہ یوزر حاصل کریں

        $posts = Post::with('user') // صرف user کا رشتہ لائیں
                    ->where('user_id', $user->id) // صرف لاگ ان یوزر کے پوسٹس
                    ->latest()
                    ->paginate(3);

        return response()->json([
            'data' => $posts->items(), 
            'next_page_url' => $posts->nextPageUrl()
        ]);
    }
}
