<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PostAnalysisService;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AIPostCleanup extends Command
{
    protected $signature = 'ai:posts-cleanup
        {--analyze : Only analyze posts, don\'t delete}
        {--dry-run : Show what would be deleted}
        {--limit=50 : Maximum posts to analyze}
        {--threshold=30 : Score threshold for deletion (lower = delete)}
        {--user= : Analyze posts from specific user}
        {--min-days=30 : Minimum age in days}
        {--export : Export analysis to JSON file}
        {--report : Generate detailed report}';
    
    protected $description = 'AI-powered post cleanup and analysis';
    
    protected $analysisService;
    
    public function __construct()
    {
        parent::__construct();
        $this->analysisService = new PostAnalysisService();
    }
    
    public function handle()
    {
        $this->info('🤖 AI Post Cleanup System');
        $this->line('========================================');
        
        $criteria = [
            'min_days' => (int)$this->option('min-days'),
            'user_id' => $this->option('user'),
            'max_views' => 10,
            'max_likes' => 5,
            'no_image' => true,
            'limit' => (int)$this->option('limit')
        ];
        
        $this->info('🔍 Finding cleanup candidates...');
        $candidates = $this->analysisService->getCleanupCandidates($criteria);
        
        if (empty($candidates)) {
            $this->warn('No posts found matching criteria.');
            return 0;
        }
        
        $this->info('📊 Found ' . count($candidates) . ' posts to analyze');
        
        // Generate report if requested
        if ($this->option('report')) {
            $report = $this->analysisService->generateReport($candidates);
            $this->displayReport($report);
            
            if ($this->option('export')) {
                $this->exportReport($report, $candidates);
            }
            
            if ($this->option('analyze')) {
                return 0;
            }
        }
        
        // Display analysis table
        $this->displayAnalysisTable($candidates);
        
        // Calculate statistics
        $stats = $this->calculateStatistics($candidates);
        $this->displayStatistics($stats);
        
        // Export if requested
        if ($this->option('export') && !$this->option('report')) {
            $this->exportAnalysis($candidates, $stats);
        }
        
        // If analyze only mode
        if ($this->option('analyze')) {
            $this->info('📋 Analysis complete. No posts were deleted.');
            return 0;
        }
        
        // Get posts to delete based on threshold
        $postsToDelete = array_filter($candidates, function($analysis) {
            return $analysis['score'] <= (int)$this->option('threshold');
        });
        
        if (empty($postsToDelete)) {
            $this->warn('No posts meet the deletion threshold (score <= ' . $this->option('threshold') . ')');
            return 0;
        }
        
        $this->warn('⚠️  Found ' . count($postsToDelete) . ' posts recommended for deletion');
        
        // DRY RUN MODE
        if ($this->option('dry-run')) {
            $this->info('🔍 DRY RUN - No posts will be deleted');
            $this->displayDeletionPreview($postsToDelete);
            return 0;
        }
        
        // CONFIRM DELETION
        $this->warn('🚨 WARNING: This will permanently delete ' . count($postsToDelete) . ' posts!');
        
        if (!$this->confirm('❓ Are you sure you want to delete these posts?')) {
            $this->info('❌ Operation cancelled.');
            return 0;
        }
        
        // PERFORM DELETION
        $deleted = $this->deletePosts($postsToDelete);
        
        $this->info('✅ Successfully deleted ' . $deleted['success'] . ' posts');
        if ($deleted['failed'] > 0) {
            $this->error('❌ Failed to delete ' . $deleted['failed'] . ' posts');
        }
        
        // Show storage savings
        if ($deleted['storage_saved'] > 0) {
            $this->info('💾 Estimated storage saved: ' . $this->formatBytes($deleted['storage_saved']));
        }
        
        // Log the cleanup operation
        Log::info('AI Post Cleanup completed', [
            'deleted' => $deleted['success'],
            'failed' => $deleted['failed'],
            'threshold' => $this->option('threshold'),
            'storage_saved' => $deleted['storage_saved']
        ]);
        
        return $deleted['failed'] > 0 ? 1 : 0;
    }
    
    /**
     * Display analysis results in table
     */
    private function displayAnalysisTable(array $analyses): void
    {
        $this->line('📋 Post Analysis Results:');
        
        $tableData = [];
        $counter = 0;
        
        foreach ($analyses as $analysis) {
            $counter++;
            if ($counter > 20) {
                $tableData[] = ['...', '...', '...', '...', '...'];
                break;
            }
            
            $tableData[] = [
                'ID' => $analysis['post_id'],
                'Title' => substr($analysis['title'], 0, 25) . (strlen($analysis['title']) > 25 ? '...' : ''),
                'Score' => $this->formatScore($analysis['score']),
                'Action' => $this->formatAction($analysis['action']),
                'Main Reason' => $analysis['reasons'][0] ?? 'N/A'
            ];
        }
        
        $this->table(['ID', 'Title', 'Score', 'Action', 'Main Reason'], $tableData);
    }
    
    /**
     * Display detailed report
     */
    private function displayReport(array $report): void
    {
        $this->info('📈 Detailed Analysis Report');
        $this->line('----------------------------------------');
        
        // Summary
        $this->line('📊 Summary:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Posts Analyzed', $report['summary']['total_posts']],
                ['Recommended to Delete', $report['summary']['recommended_delete']],
                ['Needs Manual Review', $report['summary']['recommended_review']],
                ['Recommended to Keep', $report['summary']['recommended_keep']],
                ['Average Score', $report['summary']['avg_score']],
                ['Avg Score (Delete)', $report['summary']['avg_delete_score']]
            ]
        );
        
        // Score distribution
        $this->line('📈 Score Distribution:');
        $scoreTable = [];
        foreach ($report['posts_by_score_range'] as $range => $count) {
            $percentage = $report['summary']['total_posts'] > 0 ? 
                round(($count / $report['summary']['total_posts']) * 100, 1) : 0;
            $scoreTable[] = [$range, $count, $percentage . '%'];
        }
        $this->table(['Score Range', 'Count', 'Percentage'], $scoreTable);
        
        // Age distribution
        $this->line('📅 Age Distribution:');
        $ageTable = [];
        foreach ($report['posts_by_age'] as $ageRange => $count) {
            $percentage = $report['summary']['total_posts'] > 0 ? 
                round(($count / $report['summary']['total_posts']) * 100, 1) : 0;
            $ageTable[] = [$ageRange, $count, $percentage . '%'];
        }
        $this->table(['Age Range', 'Count', 'Percentage'], $ageTable);
        
        // Top reasons
        $this->line('⚠️ Top Issues Found:');
        $reasonTable = [];
        $counter = 0;
        foreach ($report['top_reasons'] as $reason => $count) {
            $counter++;
            if ($counter > 8) break;
            $reasonTable[] = [$reason, $count];
        }
        $this->table(['Issue', 'Count'], $reasonTable);
    }
    
    /**
     * Format score with color
     */
    private function formatScore($score): string
    {
        if ($score <= 30) {
            return '<fg=red>' . $score . '</>';
        } elseif ($score <= 60) {
            return '<fg=yellow>' . $score . '</>';
        } else {
            return '<fg=green>' . $score . '</>';
        }
    }
    
    /**
     * Format action with emoji
     */
    private function formatAction(string $action): string
    {
        return match($action) {
            'delete' => '❌ Delete',
            'review' => '⚠️ Review',
            'keep' => '✅ Keep',
            default => $action
        };
    }
    
    /**
     * Calculate statistics
     */
    private function calculateStatistics(array $analyses): array
    {
        $stats = [
            'total' => count($analyses),
            'delete' => 0,
            'review' => 0,
            'keep' => 0,
            'avg_score' => 0,
            'lowest_score' => 100,
            'highest_score' => 0
        ];
        
        $totalScore = 0;
        
        foreach ($analyses as $analysis) {
            $stats[$analysis['action']]++;
            $totalScore += $analysis['score'];
            
            if ($analysis['score'] < $stats['lowest_score']) {
                $stats['lowest_score'] = $analysis['score'];
            }
            
            if ($analysis['score'] > $stats['highest_score']) {
                $stats['highest_score'] = $analysis['score'];
            }
        }
        
        $stats['avg_score'] = $stats['total'] > 0 ? round($totalScore / $stats['total'], 2) : 0;
        
        return $stats;
    }
    
    /**
     * Display statistics
     */
    private function displayStatistics(array $stats): void
    {
        $this->line('📈 Quick Statistics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Analyzed', $stats['total']],
                ['To Delete', $stats['delete']],
                ['To Review', $stats['review']],
                ['To Keep', $stats['keep']],
                ['Average Score', $stats['avg_score']],
                ['Lowest Score', $stats['lowest_score']],
                ['Highest Score', $stats['highest_score']]
            ]
        );
    }
    
    /**
     * Display deletion preview
     */
    private function displayDeletionPreview(array $postsToDelete): void
    {
        $this->line('🗑️  Posts that would be deleted:');
        
        $previewData = [];
        $counter = 0;
        
        foreach ($postsToDelete as $analysis) {
            $counter++;
            if ($counter > 15) {
                $previewData[] = ['...', '...', '...', '...'];
                $previewData[] = ['+ ' . (count($postsToDelete) - 15) . ' more', '', '', ''];
                break;
            }
            
            $previewData[] = [
                'ID' => $analysis['post_id'],
                'Title' => substr($analysis['title'], 0, 30) . (strlen($analysis['title']) > 30 ? '...' : ''),
                'Score' => $analysis['score'],
                'Reasons' => implode(', ', array_slice($analysis['reasons'], 0, 2))
            ];
        }
        
        $this->table(['ID', 'Title', 'Score', 'Reasons'], $previewData);
    }
    
    /**
     * Delete posts
     */
    private function deletePosts(array $postsToDelete): array
    {
        $result = [
            'success' => 0,
            'failed' => 0,
            'storage_saved' => 0
        ];
        
        $total = count($postsToDelete);
        $this->info('🗑️  Deleting ' . $total . ' posts...');
        
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();
        
        foreach ($postsToDelete as $analysis) {
            try {
                $post = Post::find($analysis['post_id']);
                
                if ($post) {
                    // Delete associated image if exists
                    if ($post->image) {
                        try {
                            $imagePath = 'public/' . $post->image;
                            if (Storage::exists($imagePath)) {
                                $fileSize = Storage::size($imagePath);
                                Storage::delete($imagePath);
                                $result['storage_saved'] += $fileSize;
                            }
                        } catch (\Exception $e) {
                            Log::warning('Failed to delete image for post ' . $post->id . ': ' . $e->getMessage());
                        }
                    }
                    
                    // Delete the post (cascading deletes should handle likes/comments)
                    $post->delete();
                    
                    $result['success']++;
                }
            } catch (\Exception $e) {
                Log::error('AI Post Cleanup: Failed to delete post ' . ($analysis['post_id'] ?? 'unknown'), [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $result['failed']++;
            }
            
            $progressBar->advance();
            
            // Small delay to prevent overwhelming the system
            usleep(100000); // 100ms
        }
        
        $progressBar->finish();
        $this->newLine();
        
        return $result;
    }
    
    /**
     * Export analysis to JSON file
     */
    private function exportAnalysis(array $analyses, array $stats): void
    {
        $exportData = [
            'analysis_date' => now()->toDateTimeString(),
            'statistics' => $stats,
            'posts' => $analyses,
            'criteria_used' => [
                'min_days' => $this->option('min-days'),
                'threshold' => $this->option('threshold'),
                'limit' => $this->option('limit'),
                'user' => $this->option('user')
            ],
            'command' => $this->signature
        ];
        
        $filename = 'post_analysis_' . date('Y-m-d_H-i-s') . '.json';
        $path = storage_path('app/analyses/' . $filename);
        
        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        file_put_contents($path, json_encode($exportData, JSON_PRETTY_PRINT));
        
        $this->info('💾 Analysis exported to: ' . $path);
    }
    
    /**
     * Export report
     */
    private function exportReport(array $report, array $analyses): void
    {
        $exportData = [
            'report_date' => now()->toDateTimeString(),
            'report' => $report,
            'sample_posts' => array_slice($analyses, 0, 20),
            'criteria_used' => [
                'min_days' => $this->option('min-days'),
                'limit' => $this->option('limit')
            ]
        ];
        
        $filename = 'cleanup_report_' . date('Y-m-d_H-i-s') . '.json';
        $path = storage_path('app/reports/' . $filename);
        
        // Ensure directory exists
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        
        file_put_contents($path, json_encode($exportData, JSON_PRETTY_PRINT));
        
        $this->info('📄 Report exported to: ' . $path);
    }
    
    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}