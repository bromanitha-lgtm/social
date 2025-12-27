<?php
// app/Http/Controllers/ErrorController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Show 404 error page
     */
    public function notFound()
    {
        return view('errors.404', [
            'title' => '404 - Page Not Found',
            'message' => 'The page you are looking for could not be found.'
        ]);
    }

    /**
     * Show 500 error page
     */
    public function serverError(\Exception $exception = null)
    {
        return view('errors.500', [
            'title' => '500 - Server Error',
            'exception' => $exception
        ]);
    }

    /**
     * Show 403 error page
     */
    public function forbidden()
    {
        return view('errors.403', [
            'title' => '403 - Access Denied',
            'message' => 'You do not have permission to access this resource.'
        ]);
    }

    /**
     * Show 419 error page
     */
    public function pageExpired()
    {
        return view('errors.419', [
            'title' => '419 - Page Expired',
            'message' => 'Your session has expired. Please refresh and try again.'
        ]);
    }

    /**
     * Show 503 error page
     */
    public function serviceUnavailable()
    {
        return view('errors.503', [
            'title' => '503 - Service Unavailable',
            'message' => 'The service is temporarily unavailable. Please try again later.'
        ]);
    }

    /**
     * Show custom error page
     */
    public function show($code, $message = null)
    {
        $view = "errors.{$code}";
        
        if (!view()->exists($view)) {
            $view = 'errors.500';
            $code = 500;
        }

        return response()->view($view, [
            'title' => $this->getErrorTitle($code),
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Get error title by code
     */
    private function getErrorTitle($code)
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Page Not Found',
            419 => 'Page Expired',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
        ];

        return $titles[$code] ?? 'Error';
    }
}