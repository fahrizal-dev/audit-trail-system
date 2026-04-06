"""
Audit Trail Client Library for Python

Library untuk integrasi dengan Audit Trail API
Bisa digunakan di Django, Flask, FastAPI, dll

@version 1.0.0
@author Audit Trail System
"""

import requests
import time
from typing import Optional, Dict, Any


class AuditTrailClient:
    """
    Client untuk komunikasi dengan Audit Trail API
    """
    
    def __init__(self, base_url: str, user_id: str, password: str, debug: bool = False):
        """
        Constructor
        
        Args:
            base_url: URL base audit trail (contoh: http://localhost/audit-trail/api)
            user_id: ID aplikasi dari registrasi
            password: Password aplikasi
            debug: Enable debug mode (default: False)
        """
        self.base_url = base_url.rstrip('/')
        self.user_id = user_id
        self.password = password
        self.token = None
        self.token_expiry = None
        self.debug = debug
    
    def _get_token(self) -> Optional[str]:
        """
        Get atau refresh token
        
        Returns:
            Token atau None jika gagal
        """
        # Jika token masih valid, return token yang ada
        if self.token and self.token_expiry and time.time() < self.token_expiry:
            return self.token
        
        try:
            response = requests.get(
                f'{self.base_url}/getToken',
                headers={
                    'x-userid': self.user_id,
                    'x-password': self.password
                },
                timeout=10
            )
            
            data = response.json()
            
            if self.debug:
                print(f'Audit Trail - Get Token Response: {data}')
            
            if response.status_code == 200 and 'response' in data and 'token' in data['response']:
                self.token = data['response']['token']
                # Token berlaku 15 menit, kita set 14 menit untuk safety
                self.token_expiry = time.time() + 840
                return self.token
            
            print(f'Audit Trail - Failed to get token: {data}')
            return None
            
        except Exception as e:
            print(f'Audit Trail - Error getting token: {e}')
            return None
    
    def log(self, user: str, aksi: str, **options) -> Dict[str, Any]:
        """
        Kirim log aktivitas ke audit trail
        
        Args:
            user: Username yang melakukan aktivitas
            aksi: Jenis aksi (CREATE, UPDATE, DELETE, LOGIN, LOGOUT, dll)
            **options: Optional parameters:
                - menu_fitur: Nama menu/fitur (str)
                - hasil: success atau failed (default: success)
                - no_rm: Nomor rekam medis (str)
                - rawat: JALAN atau INAP (str)
                - trx_id: ID transaksi (str)
                - ip_address: IP address user (str, auto-detect jika kosong)
                - ket: Keterangan custom (str, auto-generate jika kosong)
        
        Returns:
            Dict dengan keys: success, message, code, data
        """
        token = self._get_token()
        
        if not token:
            return {
                'success': False,
                'message': 'Failed to get token',
                'code': 0
            }
        
        # Build data
        data = {
            'user': user,
            'aksi': aksi.upper()
        }
        
        # Merge optional parameters
        if 'menu_fitur' in options:
            data['menu_fitur'] = options['menu_fitur']
        if 'hasil' in options:
            data['hasil'] = options['hasil']
        if 'no_rm' in options:
            data['no_rm'] = options['no_rm']
        if 'rawat' in options:
            data['rawat'] = options['rawat'].upper()
        if 'trx_id' in options:
            data['trx_id'] = options['trx_id']
        if 'ip_address' in options:
            data['ip_address'] = options['ip_address']
        if 'ket' in options:
            data['ket'] = options['ket']
        
        try:
            response = requests.post(
                f'{self.base_url}/postToken',
                headers={
                    'x-token': token,
                    'Content-Type': 'application/json'
                },
                json={'data': data},
                timeout=10
            )
            
            result = response.json()
            
            if self.debug:
                print(f'Audit Trail - Log Response: {result}')
            
            # Jika token expired, reset dan retry sekali
            if response.status_code == 401 and 'metadata' in result:
                if 'expired' in result['metadata'].get('message', '').lower():
                    self.token = None
                    self.token_expiry = None
                    
                    # Retry sekali
                    return self.log(user, aksi, **options)
            
            return {
                'success': response.status_code == 200,
                'message': result.get('metadata', {}).get('message', 'Unknown error'),
                'code': response.status_code,
                'data': result
            }
            
        except Exception as e:
            print(f'Audit Trail - Error sending log: {e}')
            return {
                'success': False,
                'message': str(e),
                'code': 0
            }
    
    def log_create(self, user: str, menu: str, keterangan: Optional[str] = None) -> Dict[str, Any]:
        """Shortcut untuk log CREATE"""
        return self.log(user, 'CREATE', menu_fitur=menu, ket=keterangan)
    
    def log_update(self, user: str, menu: str, keterangan: Optional[str] = None) -> Dict[str, Any]:
        """Shortcut untuk log UPDATE"""
        return self.log(user, 'UPDATE', menu_fitur=menu, ket=keterangan)
    
    def log_delete(self, user: str, menu: str, keterangan: Optional[str] = None) -> Dict[str, Any]:
        """Shortcut untuk log DELETE"""
        return self.log(user, 'DELETE', menu_fitur=menu, ket=keterangan)
    
    def log_login(self, user: str) -> Dict[str, Any]:
        """Shortcut untuk log LOGIN"""
        return self.log(
            user, 
            'LOGIN', 
            menu_fitur='Authentication',
            ket=f'{user} berhasil login ke sistem'
        )
    
    def log_logout(self, user: str) -> Dict[str, Any]:
        """Shortcut untuk log LOGOUT"""
        return self.log(
            user,
            'LOGOUT',
            menu_fitur='Authentication',
            ket=f'{user} telah logout dari sistem'
        )
    
    def enable_debug(self):
        """Enable debug mode"""
        self.debug = True
    
    def disable_debug(self):
        """Disable debug mode"""
        self.debug = False
