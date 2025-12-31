@extends('test.posts.app')

@section('title', 'Debug Image')

@section('content')
<div class="container">
    <h2>Image Debug Information</h2>
    
    @php
        $testPost = App\Models\Post::first();
    @endphp
    
    @if($testPost)
        <div class="card">
            <div class="card-header">Post Information</div>
            <div class="card-body">
                <p><strong>Post ID:</strong> {{ $testPost->id }}</p>
                <p><strong>Post Title:</strong> {{ $testPost->title }}</p>
                <p><strong>Image Path in DB:</strong> {{ $testPost->image }}</p>
                <p><strong>Full Storage Path:</strong> {{ storage_path('app/public/' . $testPost->image) }}</p>
                <p><strong>Asset URL:</strong> {{ asset('storage/' . $testPost->image) }}</p>
                <p><strong>File Exists:</strong> 
                    @if(file_exists(storage_path('app/public/' . $testPost->image)))
                        ✅ Yes
                    @else
                        ❌ No
                    @endif
                </p>
                
                <!-- Try to display image -->
                <div class="mt-3">
                    <h5>Image Display Test:</h5>
                    @if($testPost->image)
                        <img src="{{ asset('storage/' . $testPost->image) }}" 
                             class="img-thumbnail" 
                             style="max-height: 300px;">
                        <p class="mt-2">
                            <small>If image not showing, right-click → Open image in new tab</small>
                        </p>
                    @else
                        <p class="text-warning">No image for this post</p>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            No posts found in database. Create a post first.
        </div>
    @endif
    
    <!-- Test File Upload -->
    <div class="card mt-4">
        <div class="card-header">Test File Upload</div>
        <div class="card-body">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label>Test Image Upload:</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                </div>
                <div class="mb-3">
                    <label>Title:</label>
                    <input type="text" name="title" class="form-control" value="Test Image Post">
                </div>
                <div class="mb-3">
                    <label>Content:</label>
                    <textarea name="content" class="form-control">Testing image upload</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Upload Test Image</button>
            </form>
        </div>
    </div>
</div>
@endsection