<?php

namespace App\Services;

use App\Models\User;
use App\Models\Session;
use Illuminate\Support\Facades\Log;

/**
 * NotificationService
 * 
 * Handles multi-channel communication (Email, Push, SMS).
 */
class NotificationService extends BaseService
{
    public function getServiceName(): string
    {
        return 'NotificationService';
    }

    /**
     * Send an absence alert to a student.
     * In a real app, this would use Mail or Notifications facades.
     */
    public function sendAbsenceAlert(User $user, Session $session): bool
    {
        $this->logInfo("Sending absence alert to user {$user->id} for session {$session->id}");
        
        // Example implementation hook
        Log::info("EMAIL SENT: Dear {$user->name}, you were marked absent for module ID {$session->module_id}.");
        
        return true;
    }
    
    /**
     * Push a reminder for an upcoming session.
     */
    public function pushSessionReminder(User $user, Session $session): bool
    {
        $this->logInfo("Pushing session reminder to user {$user->id}");
        return true;
    }
}
