<?php
/**
 * Audit Trail Client Library for PHP
 * 
 * Library untuk integrasi dengan Audit Trail API
 * Tinggal copy file ini ke project dan gunakan!
 * 
 * @version 1.0.0
 * @author Audit Trail System
 */

class AuditTrailClient {
    
    private $baseUrl;
    private $userId;
    private $password;
    private $token = null;
    private $tokenExpiry = null;
    private $debug = false;
    
    /**
     * Constructor
     * 
     * @param string $baseUrl URL base audit trail (contoh: http://localhost/audit-trail/api)
     * @param string $userId ID aplikasi dari registrasi
     * @param string $password Password aplikasi
     * @param bool $debug Enable debug mode (default: false)
     */
    public function __construct($baseUrl, $userId, $password, $debug = false) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->userId = $userId;
        $this->password = $password;
        $this->debug = $debug;
    }
    
    /**
     * Get atau refresh token
     * 
     * @return string|null Token atau null jika gagal
     */
    private function getToken() {
        // Jika token masih valid, return token yang ada
        if ($this->token && $this->tokenExpiry && time() < $this->tokenExpiry) {
            return $this->token;
        }
        
        // Request token baru
        $ch = curl_init($this->baseUrl . '/getToken');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-userid: ' . $this->userId,
            'x-password: ' . $this->password
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($this->debug) {
            error_log("Audit Trail - Get Token Response: " . $response);
        }
        
        if ($httpCode !== 200) {
            error_log("Audit Trail - Failed to get token. HTTP Code: " . $httpCode);
            return null;
        }
        
        $data = json_decode($response, true);
        
        if (isset($data['response']['token'])) {
            $this->token = $data['response']['token'];
            // Token berlaku 15 menit, kita set 14 menit untuk safety
            $this->tokenExpiry = time() + 840;
            return $this->token;
        }
        
        return null;
    }
    
    /**
     * Kirim log aktivitas ke audit trail
     * 
     * @param string $user Username yang melakukan aktivitas
     * @param string $aksi Jenis aksi (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, dll)
     * @param array $options Optional parameters:
     *   - menu_fitur: Nama menu/fitur (string)
     *   - hasil: success atau failed (default: success)
     *   - no_rm: Nomor rekam medis (string)
     *   - rawat: JALAN atau INAP (string)
     *   - trx_id: ID transaksi (string)
     *   - ip_address: IP address user (string, auto-detect jika kosong)
     *   - ket: Keterangan custom (string, auto-generate jika kosong)
     * 
     * @return array Response dari API
     */
    public function log($user, $aksi, $options = []) {
        $token = $this->getToken();
        
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to get token',
                'code' => 0
            ];
        }
        
        // Build data
        $data = [
            'user' => $user,
            'aksi' => strtoupper($aksi)
        ];
        
        // Merge optional parameters
        if (isset($options['menu_fitur'])) $data['menu_fitur'] = $options['menu_fitur'];
        if (isset($options['hasil'])) $data['hasil'] = $options['hasil'];
        if (isset($options['no_rm'])) $data['no_rm'] = $options['no_rm'];
        if (isset($options['rawat'])) $data['rawat'] = strtoupper($options['rawat']);
        if (isset($options['trx_id'])) $data['trx_id'] = $options['trx_id'];
        if (isset($options['ip_address'])) $data['ip_address'] = $options['ip_address'];
        if (isset($options['ket'])) $data['ket'] = $options['ket'];
        
        // Send request
        $ch = curl_init($this->baseUrl . '/postToken');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'x-token: ' . $token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['data' => $data]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($this->debug) {
            error_log("Audit Trail - Log Response: " . $response);
        }
        
        $result = json_decode($response, true);
        
        // Jika token expired, reset dan retry sekali
        if ($httpCode === 401 && isset($result['metadata']['message'])) {
            if (strpos(strtolower($result['metadata']['message']), 'expired') !== false) {
                $this->token = null;
                $this->tokenExpiry = null;
                
                // Retry sekali
                return $this->log($user, $aksi, $options);
            }
        }
        
        return [
            'success' => $httpCode === 200,
            'message' => $result['metadata']['message'] ?? 'Unknown error',
            'code' => $httpCode,
            'data' => $result
        ];
    }
    
    /**
     * Shortcut untuk log CREATE
     */
    public function logCreate($user, $menu, $keterangan = null) {
        return $this->log($user, 'CREATE', [
            'menu_fitur' => $menu,
            'ket' => $keterangan
        ]);
    }
    
    /**
     * Shortcut untuk log UPDATE
     */
    public function logUpdate($user, $menu, $keterangan = null) {
        return $this->log($user, 'UPDATE', [
            'menu_fitur' => $menu,
            'ket' => $keterangan
        ]);
    }
    
    /**
     * Shortcut untuk log DELETE
     */
    public function logDelete($user, $menu, $keterangan = null) {
        return $this->log($user, 'DELETE', [
            'menu_fitur' => $menu,
            'ket' => $keterangan
        ]);
    }
    
    /**
     * Shortcut untuk log LOGIN
     */
    public function logLogin($user) {
        return $this->log($user, 'LOGIN', [
            'menu_fitur' => 'Authentication',
            'ket' => "$user berhasil login ke sistem"
        ]);
    }
    
    /**
     * Shortcut untuk log LOGOUT
     */
    public function logLogout($user) {
        return $this->log($user, 'LOGOUT', [
            'menu_fitur' => 'Authentication',
            'ket' => "$user telah logout dari sistem"
        ]);
    }
    
    /**
     * Enable debug mode
     */
    public function enableDebug() {
        $this->debug = true;
    }
    
    /**
     * Disable debug mode
     */
    public function disableDebug() {
        $this->debug = false;
    }
}
