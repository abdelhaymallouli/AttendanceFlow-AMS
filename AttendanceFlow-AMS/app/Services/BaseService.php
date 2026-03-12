<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * Base class that all domain services should extend.
 * Provides basic logging, telemetry, and common utilities.
 */
abstract class BaseService
{
    /**
     * Automatically log when the service boots up.
     */
    public function boot(): void
    {
        Log::debug("Service booted: " . $this->getServiceName());
    }

    /**
     * Standardized error logging for services.
     */
    protected function logError(string $message, \Exception $e = null, array $context = []): void
    {
        if ($e) {
            $context['exception'] = $e->getMessage();
            $context['trace'] = $e->getTraceAsString();
        }
        
        Log::error("[{$this->getServiceName()}] $message", $context);
    }
    
    /**
     * Standardized info logging for services.
     */
    protected function logInfo(string $message, array $context = []): void
    {
        Log::info("[{$this->getServiceName()}] $message", $context);
    }
}
