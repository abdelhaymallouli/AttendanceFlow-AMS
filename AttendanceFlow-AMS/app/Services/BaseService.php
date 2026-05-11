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
    protected function logError(string $message, ?\Exception $e = null, array $context = []): void
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
        $this->logMessage('info', $message, $context);
    }

    /**
     * Helper to get the service name automatically.
     */
    protected function getServiceName(): string
    {
        return class_basename($this);
    }

    /**
     * Internal logging helper.
     */
    private function logMessage(string $level, string $message, array $context = []): void
    {
        Log::$level("[{$this->getServiceName()}] $message", $context);
    }
}
