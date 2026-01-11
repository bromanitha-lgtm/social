<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:cleanup 
        {--dry-run : Show what would be deleted without actually deleting}
        {--days=30 : Remove posts older than X days}
        {--no-image : Remove posts without images}
        {--min-views=0 : Remove posts with views less than specified}
        {--min-likes=0 : Remove posts with likes less than specified}
        {--status= : Filter by post status (draft, published)}
        {--user= : Filter by user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up posts based on various criteria using AI analysis';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Post Cleanup Process...');
        $this->line('========================================');
        
        // Build query based on options
        $query = Post::query();
        
        // Filter by days (older than X days)
        if ($days = $this->option('days')) {
            $date = now()->subDays($days);
            $query->where('created_at', '<', $date);
            $this->info("📅 Filtering posts older than {$days} days (before {$date->format('Y-m-d')})");
        }
        
        // Filter by image presence
        if ($this->option('no-image')) {
            $query->where(function($q) {
                $q->whereNull('image')
                  ->orWhere('image', '');
            });
            $this->info("🖼️  Filtering posts without images");
        }
        
        // Filter by minimum views
        if ($minViews = $this->option('min-views')) {
            $query->where('views', '<', $minViews);
            $this->info("👁️  Filtering posts with less than {$minViews} views");
        }
        
        // Filter by minimum likes
        if ($minLikes = $this->option('min-likes')) {
            $query->where('likes_count', '<', $minLikes);
            $this->info("❤️  Filtering posts with less than {$minLikes} likes");
        }
        
        // Filter by status
        if ($status = $this->option('status')) {
            $query->where('status', $status);
            $this->info("📝 Filtering posts with status: {$status}");
        }
        
        // Filter by user
        if ($userId = $this->option('user')) {
            $query->where('user_id', $userId);
            $this->info("👤 Filtering posts by user ID: {$userId}");
        }
        
        // Get posts
        $posts = $query->with('user')->get();
        $count = $posts->count();
        
        $this->line('----------------------------------------');
        $this->info("🔍 Found {$count} posts matching criteria");
        
        if ($count === 0) {
            $this->warn('No posts found matching the criteria.');
            return 0;
        }
        
        // Display summary
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Posts', $count],
                ['With Images', $posts->whereNotNull('image')->count()],
                ['Average Views', round($posts->avg('views'), 2)],
                ['Average Likes', round($posts->avg('likes_count'), 2)],
                ['Oldest Post', $posts->min('created_at')?->format('Y-m-d')],
                ['Newest Post', $posts->max('created_at')?->format('Y-m-d')]
            ]
        );
        
        // DRY RUN MODE
        if ($this->option('dry-run')) {
            $this->info('📋 DRY RUN MODE - No posts will be deleted');
            $this->line('');
            
            // Show sample of posts
            $this->table(
                ['ID', 'Title', 'User', 'Created', 'Image', 'Views', 'Likes', 'Status'],
                $posts->take(10)->map(function($post) {
                    return [
                        $post->id,
                        substr($post->title, 0, 30) . (strlen($post->title) > 30 ? '...' : ''),
                        $post->user ? $post->user->fName : 'N/A',
                        $post->created_at->format('Y-m-d'),
                        $post->image ? '✅' : '❌',
                        $post->views,
                        $post->likes_count,
                        $post->status
                    ];
                })
            );
            
            if ($count > 10) {
                $this->info("... and " . ($count - 10) . " more posts.");
            }
            
            $this->line('');
            $this->warn('⚠️  This was a dry run. No posts were deleted.');
            $this->info('💡 To actually delete posts, run without --dry-run flag');
            
            return 0;
        }
        
        // ACTUAL DELETION
        $this->warn('⚠️  WARNING: This will permanently delete posts!');
        
        if (!$this->confirm("❓ Are you sure you want to delete {$count} posts?")) {
            $this->info('❌ Operation cancelled.');
            return 0;
        }
        
        $deleted = 0;
        $errors = 0;
        
        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();
        
        foreach ($posts as $post) {
            try {
                // Delete image from storage if exists
                if ($post->image && Storage::disk('public')->exists($post->image)) {
                    Storage::disk('public')->delete($post->image);
                    $this->line("\n🗑️  Deleted image: " . $post->image, 'verbose');
                }
                
                // Delete associated likes and comments
                $post->likes()->delete();
                $post->comments()->delete();
                
                // Delete the post
                $post->delete();
                
                $deleted++;
                
                // Log the deletion
                Log::info('Post deleted via cleanup command', [
                    'post_id' => $post->id,
                    'title' => $post->title,
                    'user_id' => $post->user_id,
                    'deleted_at' => now()
                ]);
                
            } catch (\Exception $e) {
                $errors++;
                Log::error('Failed to delete post: ' . $e->getMessage(), [
                    'post_id' => $post->id,
                    'error' => $e->getMessage()
                ]);
                
                $this->error("\nFailed to delete post ID {$post->id}: " . $e->getMessage());
            }
            
            $progressBar->advance();
            
            // Small delay to prevent overwhelming the system
            usleep(50000); // 50ms
        }
        
        $progressBar->finish();
        $this->line('');
        $this->line('----------------------------------------');
        
        if ($errors > 0) {
            $this->error("❌ Completed with {$errors} error(s)");
            $this->info("✅ Successfully deleted: {$deleted} posts");
            $this->info("❌ Failed to delete: {$errors} posts");
        } else {
            $this->info("✅ Successfully deleted {$deleted} posts!");
        }
        
        // Show storage space saved (estimated)
        $estimatedSpace = $count * 500; // Assuming ~500KB per post average
        $this->info("💾 Estimated storage saved: " . $this->formatBytes($estimatedSpace));
        
        return $errors > 0 ? 1 : 0;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}