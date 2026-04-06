<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Audit Trail Configuration
 * 
 * Konfigurasi untuk sistem Audit Trail
 */

// ============================================
// TOKEN CONFIGURATION
// ============================================

/**
 * Token Expiry Duration
 * 
 * Durasi token sebelum expired (dalam menit)
 * 
 * Rekomendasi:
 * - Development: 5-10 menit
 * - Production: 15-30 menit
 * - Internal System: 60 menit
 * 
 * Default: 15 menit
 */
$config['audit_token_expiry'] = 15;

/**
 * Token Auto-Extend
 * 
 * Apakah token otomatis diperpanjang setiap kali digunakan?
 * 
 * TRUE: Token akan diperpanjang setiap kali API dipanggil
 * FALSE: Token akan expired sesuai waktu awal
 * 
 * Default: TRUE (recommended)
 */
$config['audit_token_auto_extend'] = TRUE;

// ============================================
// API CONFIGURATION
// ============================================

/**
 * API Rate Limiting
 * 
 * Maksimal request per menit per aplikasi
 * 0 = unlimited
 * 
 * Default: 0 (unlimited)
 */
$config['audit_rate_limit'] = 0;

/**
 * API Timeout
 * 
 * Timeout untuk API request (dalam detik)
 * 
 * Default: 30
 */
$config['audit_api_timeout'] = 30;

// ============================================
// LOGGING CONFIGURATION
// ============================================

/**
 * Log Retention
 * 
 * Berapa lama log disimpan (dalam hari)
 * 0 = unlimited
 * 
 * Default: 0 (unlimited)
 */
$config['audit_log_retention'] = 0;

/**
 * Auto Clean Old Logs
 * 
 * Apakah otomatis hapus log lama?
 * 
 * Default: FALSE
 */
$config['audit_auto_clean_logs'] = FALSE;

// ============================================
// SECURITY CONFIGURATION
// ============================================

/**
 * Require HTTPS
 * 
 * Apakah wajib menggunakan HTTPS untuk API?
 * 
 * Default: FALSE (set TRUE di production)
 */
$config['audit_require_https'] = FALSE;

/**
 * IP Whitelist
 * 
 * Daftar IP yang diizinkan akses API
 * Kosongkan untuk allow all
 * 
 * Contoh: ['192.168.1.100', '10.0.0.50']
 * 
 * Default: [] (allow all)
 */
$config['audit_ip_whitelist'] = [];

/**
 * Enable Encryption
 * 
 * Apakah wajib menggunakan enkripsi RC4?
 * 
 * Default: FALSE (opsional)
 */
$config['audit_require_encryption'] = FALSE;
