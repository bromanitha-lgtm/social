<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Log;

class PostAnalysisService
{
    /**
     * Analyze a post to determine if it should be deleted
     */
    public function analyzePost(Post $post): array
    {
        $score = 100; // Start with 100 points, deduct based on issues
        $reasons = [];
        
        // 1. Content Quality Analysis
        $contentScore = $this->analyzeContent($post);
        $score += $contentScore['score'];
        if ($contentScore['reasons']) {
            $reasons = array_merge($reasons, $contentScore['reasons']);
        }
        
        // 2. Engagement Analysis
        $engagementScore = $this->analyzeEngagement($post);
        $score += $engagementScore['score'];
        if ($engagementScore['reasons']) {
            $reasons = array_merge($reasons, $engagementScore['reasons']);
        }
        
        // 3. Image Analysis (if post has image)
        if ($post->image) {
            $imageScore = $this->analyzeImage($post);
            $score += $imageScore['score'];
            if ($imageScore['reasons']) {
                $reasons = array_merge($reasons, $imageScore['reasons']);
            }
        } else {
            $score -= 15; // Deduct more points for no image
            $reasons[] = 'No image attached';
        }
        
        // 4. Time-based Analysis
        $timeScore = $this->analyzeTime($post);
        $score += $timeScore['score'];
        if ($timeScore['reasons']) {
            $reasons = array_merge($reasons, $timeScore['reasons']);
        }
        
        // Ensure score doesn't go below 0
        $score = max(0, $score);
        
        // Determine action based on score
        $action = 'keep';
        if ($score <= 30) {
            $action = 'delete';
        } elseif ($score <= 60) {
            $action = 'review';
        }
        
        return [
            'post_id' => $post->id,
            'title' => $post->title,
            'score' => $score,
            'action' => $action,
            'reasons' => array_unique($reasons),
            'analysis_time' => now()->toDateTimeString(),
            'user_id' => $post->user_id,
            'created_at' => $post->created_at->format('Y-m-d H:i:s'),
            'views' => $post->views,
            'likes' => $post->likes_count,
            'comments' => $post->comments_count,
            'has_image' => !empty($post->image)
        ];
    }
    
    /**
     * Analyze post content
     */
    private function analyzeContent(Post $post): array
    {
        $score = 0;
        $reasons = [];
        
        $content = strip_tags($post->content);
        $wordCount = str_word_count($content);
        $charCount = strlen($content);
        
        // Word count analysis (ideal: 150-1000 words)
        if ($wordCount < 50) {
            $score -= 25;
            $reasons[] = 'Content too short (' . $wordCount . ' words)';
        } elseif ($wordCount < 100) {
            $score -= 10;
            $reasons[] = 'Content somewhat short (' . $wordCount . ' words)';
        } elseif ($wordCount > 2000) {
            $score -= 5;
            $reasons[] = 'Content very long (' . $wordCount . ' words)';
        } else {
            $score += 15; // Good length
        }
        
        // Check for spam indicators
        $spamIndicators = $this->checkForSpam($content);
        if ($spamIndicators['is_spammy']) {
            $score -= 40;
            $reasons[] = 'Spam indicators: ' . implode(', ', array_slice($spamIndicators['indicators'], 0, 3));
        }
        
        // Check for readability
        $readability = $this->checkReadability($content);
        if ($readability['is_poor']) {
            $score -= 15;
            $reasons[] = 'Poor readability';
        }
        
        // Check for empty content
        if (empty(trim($content))) {
            $score -= 50;
            $reasons[] = 'Empty content';
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Analyze engagement metrics
     */
    private function analyzeEngagement(Post $post): array
    {
        $score = 0;
        $reasons = [];
        
        $views = $post->views ?? 0;
        $likes = $post->likes_count ?? 0;
        $comments = $post->comments_count ?? 0;
        $ageInDays = $post->created_at->diffInDays(now());
        
        // View analysis (expect at least 1 view per day of age)
        $expectedViews = max(10, $ageInDays);
        if ($views < $expectedViews) {
            $deduction = min(30, ($expectedViews - $views) * 2);
            $score -= $deduction;
            $reasons[] = 'Low views (' . $views . ' vs expected ' . $expectedViews . ')';
        } elseif ($views > 1000) {
            $score += 20;
        } elseif ($views > 100) {
            $score += 10;
        }
        
        // Like ratio analysis
        $likeRatio = $views > 0 ? ($likes / $views) * 100 : 0;
        if ($views > 20 && $likeRatio < 2) { // Less than 2% like ratio
            $score -= 20;
            $reasons[] = 'Poor engagement (only ' . round($likeRatio, 1) . '% likes)';
        } elseif ($likeRatio > 10) {
            $score += 15;
        } elseif ($likeRatio > 5) {
            $score += 10;
        }
        
        // Comment analysis
        if ($comments === 0 && $views > 50 && $ageInDays > 7) {
            $score -= 15;
            $reasons[] = 'No comments despite ' . $views . ' views';
        } elseif ($comments > 20) {
            $score += 15;
        } elseif ($comments > 5) {
            $score += 10;
        }
        
        // Check if post has any engagement at all
        if ($views === 0 && $ageInDays > 1) {
            $score -= 30;
            $reasons[] = 'Zero views';
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Analyze image (simplified version)
     */
    private function analyzeImage(Post $post): array
    {
        $score = 0;
        $reasons = [];
        
        // Check if image exists
        if (!$post->image) {
            return ['score' => -20, 'reasons' => ['No image']];
        }
        
        // Check if image path is valid
        $imagePath = storage_path('app/public/' . $post->image);
        
        if (!file_exists($imagePath)) {
            $score -= 25;
            $reasons[] = 'Image file missing from storage';
            return ['score' => $score, 'reasons' => $reasons];
        }
        
        try {
            // Get image info
            $imageInfo = @getimagesize($imagePath);
            if (!$imageInfo) {
                $score -= 20;
                $reasons[] = 'Invalid/corrupted image file';
                return ['score' => $score, 'reasons' => $reasons];
            }
            
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $fileSize = filesize($imagePath);
            
            // Check image dimensions (ideal: 800x600 to 1920x1080)
            if ($width < 400 || $height < 300) {
                $score -= 15;
                $reasons[] = 'Small image (' . $width . 'x' . $height . ')';
            } elseif ($width > 4000 || $height > 4000) {
                $score -= 10;
                $reasons[] = 'Oversized image (' . $width . 'x' . $height . ')';
            } else {
                $score += 10; // Good dimensions
            }
            
            // Check file size (ideal: 50KB - 2MB)
            if ($fileSize > 5242880) { // > 5MB
                $score -= 15;
                $reasons[] = 'Large file (' . round($fileSize / 1024 / 1024, 1) . 'MB)';
            } elseif ($fileSize < 10240) { // < 10KB
                $score -= 10;
                $reasons[] = 'Very small file (' . round($fileSize / 1024, 1) . 'KB)';
            } else {
                $score += 5; // Good file size
            }
            
        } catch (\Exception $e) {
            $score -= 10;
            $reasons[] = 'Error analyzing image';
            Log::error('Image analysis error: ' . $e->getMessage());
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Analyze time-based factors
     */
    private function analyzeTime(Post $post): array
    {
        $score = 0;
        $reasons = [];
        
        $createdAt = $post->created_at;
        $ageInDays = $createdAt->diffInDays(now());
        $ageInMonths = $createdAt->diffInMonths(now());
        
        // Age analysis (older posts are more likely to be irrelevant)
        if ($ageInMonths > 12) { // Older than 1 year
            $score -= 30;
            $reasons[] = 'Very old (' . $ageInMonths . ' months)';
        } elseif ($ageInMonths > 6) { // 6-12 months
            $score -= 20;
            $reasons[] = 'Old (' . $ageInMonths . ' months)';
        } elseif ($ageInMonths > 3) { // 3-6 months
            $score -= 10;
        } elseif ($ageInDays < 7) { // Less than 1 week
            $score += 10; // New posts get bonus
        }
        
        // Check if never updated (stale content)
        if ($post->created_at == $post->updated_at) {
            if ($ageInDays > 30) {
                $score -= 10;
                $reasons[] = 'Never updated (' . $ageInDays . ' days old)';
            }
        }
        
        // Check if post is scheduled but never published
        if ($post->status === 'draft' && $ageInDays > 30) {
            $score -= 25;
            $reasons[] = 'Old draft never published';
        }
        
        return ['score' => $score, 'reasons' => $reasons];
    }
    
    /**
     * Check for spam indicators in content
     */
    private function checkForSpam(string $content): array
    {
        $spamKeywords = [
            'buy now', 'click here', 'limited offer', 'make money', 'work from home',
            'get rich', 'discount', 'cheap', 'free trial', 'winner', 'prize',
            'urgent', 'important', 'act now', 'don\'t delete', 'special promotion',
            '$$$', 'earn cash', 'instant', 'guaranteed', 'risk-free', 'bonus',
            'click below', 'sign up now', 'order now', 'buy today', 'best price'
        ];
        
        $indicators = [];
        $contentLower = strtolower($content);
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($contentLower, $keyword) !== false) {
                $indicators[] = $keyword;
            }
        }
        
        // Check for excessive links
        $linkCount = preg_match_all('/https?:\/\/[^\s]+/', $content);
        if ($linkCount > 5) {
            $indicators[] = 'Excessive links (' . $linkCount . ')';
        }
        
        // Check for excessive caps
        $capsWords = preg_match_all('/\b[A-Z]{3,}\b/', $content);
        if ($capsWords > 5) {
            $indicators[] = 'Excessive capitalization';
        }
        
        // Check for phone numbers (spam indicator)
        $phonePatterns = [
            '/(?:\+\d{1,3}[- ]?)?\d{10}/', // International and local numbers
            '/\d{3}[-.]?\d{3}[-.]?\d{4}/'  // US style numbers
        ];
        
        foreach ($phonePatterns as $pattern) {
            if (preg_match_all($pattern, $content) > 2) {
                $indicators[] = 'Multiple phone numbers';
                break;
            }
        }
        
        // Check for email addresses
        $emailCount = preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $content);
        if ($emailCount > 2) {
            $indicators[] = 'Multiple email addresses';
        }
        
        return [
            'is_spammy' => count($indicators) > 3,
            'indicators' => $indicators
        ];
    }
    
    /**
     * Check content readability
     */
    private function checkReadability(string $content): array
    {
        // Simple readability checks
        $sentences = preg_split('/[.!?]+/', $content);
        $words = str_word_count($content);
        $sentenceCount = count(array_filter($sentences));
        
        if ($sentenceCount === 0) {
            return ['is_poor' => true];
        }
        
        $avgSentenceLength = $words / $sentenceCount;
        
        $isPoor = false;
        $reasons = [];
        
        if ($avgSentenceLength > 35) {
            $isPoor = true;
            $reasons[] = 'Long sentences (avg ' . round($avgSentenceLength) . ' words)';
        }
        
        if ($avgSentenceLength < 5 && $sentenceCount > 10) {
            $isPoor = true;
            $reasons[] = 'Very short sentences';
        }
        
        // Check paragraph structure
        $paragraphs = preg_split('/\n\s*\n/', $content);
        if (count($paragraphs) === 1 && $words > 300) {
            $isPoor = true;
            $reasons[] = 'No paragraph breaks';
        }
        
        // Check for run-on sentences (no punctuation for long stretches)
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            if (strlen($line) > 200 && substr_count($line, '.') < 2) {
                $isPoor = true;
                $reasons[] = 'Run-on sentences';
                break;
            }
        }
        
        return [
            'is_poor' => $isPoor,
            'reasons' => $reasons
        ];
    }
    
    /**
     * Analyze multiple posts and get recommendations
     */
    public function analyzePosts(array $postIds): array
    {
        $results = [];
        $recommendations = [
            'delete' => [],
            'review' => [],
            'keep' => []
        ];
        
        foreach ($postIds as $postId) {
            $post = Post::with('user')->find($postId);
            if ($post) {
                $analysis = $this->analyzePost($post);
                $results[] = $analysis;
                $recommendations[$analysis['action']][] = $analysis;
            }
        }
        
        return [
            'results' => $results,
            'recommendations' => $recommendations,
            'summary' => [
                'total' => count($results),
                'to_delete' => count($recommendations['delete']),
                'to_review' => count($recommendations['review']),
                'to_keep' => count($recommendations['keep'])
            ]
        ];
    }
    
    /**
     * Get posts that are likely candidates for cleanup
     */
    public function getCleanupCandidates(array $criteria = []): array
    {
        $query = Post::with('user');
        
        // Apply criteria filters
        if (!empty($criteria['min_days']) && $criteria['min_days'] > 0) {
            $query->where('created_at', '<', now()->subDays($criteria['min_days']));
        }
        
        if (!empty($criteria['max_views']) && $criteria['max_views'] > 0) {
            $query->where('views', '<', $criteria['max_views']);
        }
        
        if (!empty($criteria['max_likes']) && $criteria['max_likes'] > 0) {
            $query->where('likes_count', '<', $criteria['max_likes']);
        }
        
        if (!empty($criteria['no_image']) && $criteria['no_image']) {
            $query->where(function($q) {
                $q->whereNull('image')->orWhere('image', '');
            });
        }
        
        if (!empty($criteria['user_id']) && $criteria['user_id']) {
            $query->where('user_id', $criteria['user_id']);
        }
        
        // Only published posts (usually)
        if (empty($criteria['include_drafts'])) {
            $query->where('status', 'published');
        }
        
        // Order by worst performers first
        $query->orderBy('views', 'asc')
              ->orderBy('likes_count', 'asc')
              ->orderBy('created_at', 'asc');
        
        $limit = $criteria['limit'] ?? 50;
        $candidates = $query->limit($limit)->get();
        
        $analyses = [];
        foreach ($candidates as $post) {
            $analyses[] = $this->analyzePost($post);
        }
        
        // Sort by score (lowest first - most likely to delete)
        usort($analyses, function($a, $b) {
            return $a['score'] <=> $b['score'];
        });
        
        return $analyses;
    }
    
    /**
     * Generate a cleanup report
     */
    public function generateReport(array $analyses): array
    {
        $total = count($analyses);
        $toDelete = array_filter($analyses, fn($a) => $a['action'] === 'delete');
        $toReview = array_filter($analyses, fn($a) => $a['action'] === 'review');
        $toKeep = array_filter($analyses, fn($a) => $a['action'] === 'keep');
        
        // Calculate average scores
        $avgScore = $total > 0 ? array_sum(array_column($analyses, 'score')) / $total : 0;
        $avgDeleteScore = count($toDelete) > 0 ? 
            array_sum(array_column($toDelete, 'score')) / count($toDelete) : 0;
        
        // Common reasons
        $allReasons = [];
        foreach ($analyses as $analysis) {
            foreach ($analysis['reasons'] as $reason) {
                $allReasons[$reason] = ($allReasons[$reason] ?? 0) + 1;
            }
        }
        
        arsort($allReasons);
        $topReasons = array_slice($allReasons, 0, 10, true);
        
        return [
            'summary' => [
                'total_posts' => $total,
                'recommended_delete' => count($toDelete),
                'recommended_review' => count($toReview),
                'recommended_keep' => count($toKeep),
                'avg_score' => round($avgScore, 2),
                'avg_delete_score' => round($avgDeleteScore, 2)
            ],
            'top_reasons' => $topReasons,
            'posts_by_score_range' => [
                '0-20' => count(array_filter($analyses, fn($a) => $a['score'] <= 20)),
                '21-40' => count(array_filter($analyses, fn($a) => $a['score'] > 20 && $a['score'] <= 40)),
                '41-60' => count(array_filter($analyses, fn($a) => $a['score'] > 40 && $a['score'] <= 60)),
                '61-80' => count(array_filter($analyses, fn($a) => $a['score'] > 60 && $a['score'] <= 80)),
                '81-100' => count(array_filter($analyses, fn($a) => $a['score'] > 80))
            ],
            'posts_by_age' => [
                '0-30_days' => count(array_filter($analyses, fn($a) => 
                    strtotime($a['created_at']) > strtotime('-30 days'))),
                '31-90_days' => count(array_filter($analyses, fn($a) => 
                    strtotime($a['created_at']) <= strtotime('-30 days') && 
                    strtotime($a['created_at']) > strtotime('-90 days'))),
                '91-365_days' => count(array_filter($analyses, fn($a) => 
                    strtotime($a['created_at']) <= strtotime('-90 days') && 
                    strtotime($a['created_at']) > strtotime('-365 days'))),
                'over_1_year' => count(array_filter($analyses, fn($a) => 
                    strtotime($a['created_at']) <= strtotime('-365 days')))
            ]
        ];
    }
}