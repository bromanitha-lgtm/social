{{-- resources/views/test/posts/partials/_post-cards.blade.php --}}

@forelse($posts as $post)
<div class="col-md-6 col-lg-4 mb-4">
    @include('test.posts.partials._post-card', ['post' => $post])
</div>
@empty
@if(!isset($hide_empty_message))
<div class="col-12">
    <div class="card text-center py-5">
        <div class="card-body">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h4>No posts found</h4>
            <p class="text-muted">Be the first to create a post!</p>
            @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                Create Your First Post
            </a>
            @endauth
        </div>
    </div>
</div>
@endif
@endforelse