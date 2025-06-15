<?php

namespace App\Services\PSB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;

class AuthService
{
    private $maxLoginAttempts = 5;
    private $lockoutTime = 30; // minutes
    private $sessionTimeout = 120; // minutes

    public function login($email, $password, $deviceInfo)
    {
        try {
            // Rate limiting
            if ($this->isRateLimited($email)) {
                throw new \Exception('Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $this->lockoutTime . ' menit');
            }

            // Validasi user
            $user = User::where('email', $email)->first();
            if (!$user || !Hash::check($password, $user->password)) {
                $this->incrementLoginAttempts($email);
                throw new \Exception('Email atau password salah');
            }

            // Validasi password strength
            if (!$this->isPasswordStrong($password)) {
                throw new \Exception('Password terlalu lemah. Gunakan kombinasi huruf besar, huruf kecil, angka, dan karakter khusus');
            }

            // Generate session token
            $sessionToken = Str::random(60);
            
            // Simpan session
            $this->createSession($user, $sessionToken, $deviceInfo);

            // Reset login attempts
            $this->resetLoginAttempts($email);

            // Log aktivitas
            $this->logLoginActivity($user, $deviceInfo, true);

            return [
                'user' => $user,
                'token' => $sessionToken
            ];

        } catch (\Exception $e) {
            $this->logLoginActivity(null, $deviceInfo, false, $e->getMessage());
            throw $e;
        }
    }

    public function logout($userId, $sessionToken)
    {
        try {
            // Hapus session
            Cache::forget("session:{$sessionToken}");

            // Log aktivitas
            $this->logLogoutActivity($userId);

            return true;
        } catch (\Exception $e) {
            Log::error('Error during logout: ' . $e->getMessage());
            throw $e;
        }
    }

    private function isRateLimited($email)
    {
        $attempts = Cache::get("login_attempts:{$email}", 0);
        return $attempts >= $this->maxLoginAttempts;
    }

    private function incrementLoginAttempts($email)
    {
        $attempts = Cache::get("login_attempts:{$email}", 0);
        Cache::put("login_attempts:{$email}", $attempts + 1, Carbon::now()->addMinutes($this->lockoutTime));
    }

    private function resetLoginAttempts($email)
    {
        Cache::forget("login_attempts:{$email}");
    }

    private function isPasswordStrong($password)
    {
        // Minimal 8 karakter
        if (strlen($password) < 8) {
            return false;
        }

        // Harus mengandung huruf besar
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Harus mengandung huruf kecil
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Harus mengandung angka
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Harus mengandung karakter khusus
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return false;
        }

        return true;
    }

    private function createSession($user, $sessionToken, $deviceInfo)
    {
        $sessionData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'device_info' => $deviceInfo,
            'created_at' => Carbon::now(),
            'last_activity' => Carbon::now()
        ];

        Cache::put("session:{$sessionToken}", $sessionData, Carbon::now()->addMinutes($this->sessionTimeout));
    }

    private function logLoginActivity($user, $deviceInfo, $success, $error = null)
    {
        Log::channel('auth')->info('Login attempt', [
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'device_info' => $deviceInfo,
            'success' => $success,
            'error' => $error,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()
        ]);
    }

    private function logLogoutActivity($userId)
    {
        Log::channel('auth')->info('Logout', [
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()
        ]);
    }

    public function validateSession($sessionToken)
    {
        $session = Cache::get("session:{$sessionToken}");
        
        if (!$session) {
            return false;
        }

        // Update last activity
        $session['last_activity'] = Carbon::now();
        Cache::put("session:{$sessionToken}", $session, Carbon::now()->addMinutes($this->sessionTimeout));

        return true;
    }

    public function getActiveSessions($userId)
    {
        $sessions = [];
        $keys = Cache::get("user_sessions:{$userId}", []);

        foreach ($keys as $token) {
            $session = Cache::get("session:{$token}");
            if ($session) {
                $sessions[] = $session;
            }
        }

        return $sessions;
    }

    public function invalidateAllSessions($userId)
    {
        $keys = Cache::get("user_sessions:{$userId}", []);
        
        foreach ($keys as $token) {
            Cache::forget("session:{$token}");
        }

        Cache::forget("user_sessions:{$userId}");
    }
} 