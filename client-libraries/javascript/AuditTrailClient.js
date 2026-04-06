/**
 * Audit Trail Client Library for JavaScript
 * 
 * Library untuk integrasi dengan Audit Trail API
 * Bisa digunakan di Node.js atau Browser
 * 
 * @version 1.0.0
 * @author Audit Trail System
 */

class AuditTrailClient {
    
    /**
     * Constructor
     * 
     * @param {string} baseUrl - URL base audit trail (contoh: http://localhost/audit-trail/api)
     * @param {string} userId - ID aplikasi dari registrasi
     * @param {string} password - Password aplikasi
     * @param {boolean} debug - Enable debug mode (default: false)
     */
    constructor(baseUrl, userId, password, debug = false) {
        this.baseUrl = baseUrl.replace(/\/$/, '');
        this.userId = userId;
        this.password = password;
        this.token = null;
        this.tokenExpiry = null;
        this.debug = debug;
    }
    
    /**
     * Get atau refresh token
     * 
     * @returns {Promise<string|null>} Token atau null jika gagal
     */
    async getToken() {
        // Jika token masih valid, return token yang ada
        if (this.token && this.tokenExpiry && Date.now() < this.tokenExpiry) {
            return this.token;
        }
        
        try {
            const response = await fetch(`${this.baseUrl}/getToken`, {
                method: 'GET',
                headers: {
                    'x-userid': this.userId,
                    'x-password': this.password
                }
            });
            
            const data = await response.json();
            
            if (this.debug) {
                console.log('Audit Trail - Get Token Response:', data);
            }
            
            if (response.ok && data.response?.token) {
                this.token = data.response.token;
                // Token berlaku 15 menit, kita set 14 menit untuk safety
                this.tokenExpiry = Date.now() + (840 * 1000);
                return this.token;
            }
            
            console.error('Audit Trail - Failed to get token:', data);
            return null;
            
        } catch (error) {
            console.error('Audit Trail - Error getting token:', error);
            return null;
        }
    }
    
    /**
     * Kirim log aktivitas ke audit trail
     * 
     * @param {string} user - Username yang melakukan aktivitas
     * @param {string} aksi - Jenis aksi (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, dll)
     * @param {Object} options - Optional parameters:
     *   - menu_fitur: Nama menu/fitur (string)
     *   - hasil: success atau failed (default: success)
     *   - no_rm: Nomor rekam medis (string)
     *   - rawat: JALAN atau INAP (string)
     *   - trx_id: ID transaksi (string)
     *   - ip_address: IP address user (string, auto-detect jika kosong)
     *   - ket: Keterangan custom (string, auto-generate jika kosong)
     * 
     * @returns {Promise<Object>} Response dari API
     */
    async log(user, aksi, options = {}) {
        const token = await this.getToken();
        
        if (!token) {
            return {
                success: false,
                message: 'Failed to get token',
                code: 0
            };
        }
        
        // Build data
        const data = {
            user: user,
            aksi: aksi.toUpperCase(),
            ...options
        };
        
        // Uppercase rawat jika ada
        if (data.rawat) {
            data.rawat = data.rawat.toUpperCase();
        }
        
        try {
            const response = await fetch(`${this.baseUrl}/postToken`, {
                method: 'POST',
                headers: {
                    'x-token': token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ data })
            });
            
            const result = await response.json();
            
            if (this.debug) {
                console.log('Audit Trail - Log Response:', result);
            }
            
            // Jika token expired, reset dan retry sekali
            if (response.status === 401 && result.metadata?.message?.toLowerCase().includes('expired')) {
                this.token = null;
                this.tokenExpiry = null;
                
                // Retry sekali
                return await this.log(user, aksi, options);
            }
            
            return {
                success: response.ok,
                message: result.metadata?.message || 'Unknown error',
                code: response.status,
                data: result
            };
            
        } catch (error) {
            console.error('Audit Trail - Error sending log:', error);
            return {
                success: false,
                message: error.message,
                code: 0
            };
        }
    }
    
    /**
     * Shortcut untuk log CREATE
     */
    async logCreate(user, menu, keterangan = null) {
        return await this.log(user, 'CREATE', {
            menu_fitur: menu,
            ket: keterangan
        });
    }
    
    /**
     * Shortcut untuk log UPDATE
     */
    async logUpdate(user, menu, keterangan = null) {
        return await this.log(user, 'UPDATE', {
            menu_fitur: menu,
            ket: keterangan
        });
    }
    
    /**
     * Shortcut untuk log DELETE
     */
    async logDelete(user, menu, keterangan = null) {
        return await this.log(user, 'DELETE', {
            menu_fitur: menu,
            ket: keterangan
        });
    }
    
    /**
     * Shortcut untuk log LOGIN
     */
    async logLogin(user) {
        return await this.log(user, 'LOGIN', {
            menu_fitur: 'Authentication',
            ket: `${user} berhasil login ke sistem`
        });
    }
    
    /**
     * Shortcut untuk log LOGOUT
     */
    async logLogout(user) {
        return await this.log(user, 'LOGOUT', {
            menu_fitur: 'Authentication',
            ket: `${user} telah logout dari sistem`
        });
    }
    
    /**
     * Enable debug mode
     */
    enableDebug() {
        this.debug = true;
    }
    
    /**
     * Disable debug mode
     */
    disableDebug() {
        this.debug = false;
    }
}

// Export untuk Node.js
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AuditTrailClient;
}
