<?php

namespace App\Services\PSB;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PSB\StatusChanged;
use App\Mail\PSB\WawancaraScheduled;
use App\Mail\PSB\PengumumanPublished;
use App\Mail\PSB\PaymentVerified;

class NotificationService
{
    public function sendEmail($to, $subject, $template, $data)
    {
        try {
            switch ($template) {
                case 'status_changed':
                    Mail::to($to)->send(new StatusChanged($data));
                    break;
                case 'wawancara_scheduled':
                    Mail::to($to)->send(new WawancaraScheduled($data));
                    break;
                case 'pengumuman_published':
                    Mail::to($to)->send(new PengumumanPublished($data));
                    break;
                case 'payment_verified':
                    Mail::to($to)->send(new PaymentVerified($data));
                    break;
                default:
                    throw new \Exception('Template email tidak ditemukan');
            }

            $this->logNotification('email', $to, $subject, $template, true);
            return true;
        } catch (\Exception $e) {
            $this->logNotification('email', $to, $subject, $template, false, $e->getMessage());
            return false;
        }
    }

    public function sendWhatsApp($to, $message)
    {
        try {
            // Implementasi pengiriman WhatsApp
            // Bisa menggunakan API WhatsApp Business atau layanan pihak ketiga
            
            $this->logNotification('whatsapp', $to, null, null, true);
            return true;
        } catch (\Exception $e) {
            $this->logNotification('whatsapp', $to, null, null, false, $e->getMessage());
            return false;
        }
    }

    private function logNotification($type, $recipient, $subject = null, $template = null, $success = true, $error = null)
    {
        Log::channel('notifications')->info('Notification sent', [
            'type' => $type,
            'recipient' => $recipient,
            'subject' => $subject,
            'template' => $template,
            'success' => $success,
            'error' => $error,
            'timestamp' => now()
        ]);
    }
} 