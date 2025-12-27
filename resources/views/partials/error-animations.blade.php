{{-- This file contains reusable animation styles for error pages --}}
<style>
    /* Additional animation classes for error pages */
    .bounce {
        animation: bounce 1.5s infinite;
    }
    
    .spin {
        animation: spin 3s linear infinite;
    }
    
    .wiggle {
        animation: wiggle 2.5s ease-in-out infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    @keyframes wiggle {
        0%, 100% { transform: rotate(-3deg); }
        50% { transform: rotate(3deg); }
    }
    
    /* Error-specific animations */
    .error-pulse {
        animation: errorPulse 2s ease-in-out infinite;
    }
    
    @keyframes errorPulse {
        0%, 100% { 
            opacity: 1;
            transform: scale(1);
        }
        50% { 
            opacity: 0.7;
            transform: scale(1.05);
        }
    }
    
    /* Connection animation for 500 error */
    .connection-line {
        stroke-dasharray: 10, 5;
        animation: dash 20s linear infinite;
    }
    
    @keyframes dash {
        to {
            stroke-dashoffset: 1000;
        }
    }
</style>