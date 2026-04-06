// Modern Audit Trail JavaScript

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Auto hide alerts
function autoHideAlerts() {
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.animation = 'slideInRight 0.4s ease-out reverse';
            setTimeout(() => alert.remove(), 400);
        });
    }, 5000);
}

// Modal functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// Close modal on outside click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal')) {
        hideModal(e.target.id);
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.show').forEach(modal => {
            hideModal(modal.id);
        });
    }
});

// Table row click animation
document.querySelectorAll('.table tbody tr').forEach(row => {
    row.addEventListener('click', function() {
        this.style.transform = 'scale(0.98)';
        setTimeout(() => {
            this.style.transform = '';
        }, 100);
    });
});

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = 'var(--danger)';
            isValid = false;
            
            setTimeout(() => {
                input.style.borderColor = '';
            }, 2000);
        }
    });
    
    return isValid;
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
    });
}

// Toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.top = '24px';
    toast.style.right = '24px';
    toast.style.zIndex = '9999';
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideInRight 0.4s ease-out reverse';
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

// Loading spinner
function showLoading() {
    const loader = document.createElement('div');
    loader.id = 'globalLoader';
    loader.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); 
                    display: flex; align-items: center; justify-content: center; z-index: 99999;">
            <div style="width: 50px; height: 50px; border: 4px solid rgba(255,255,255,0.3); 
                        border-top-color: white; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        </div>
    `;
    document.body.appendChild(loader);
}

function hideLoading() {
    const loader = document.getElementById('globalLoader');
    if (loader) loader.remove();
}

// Add spin animation
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Export with loading
document.querySelectorAll('a[href*="export"]').forEach(link => {
    link.addEventListener('click', function(e) {
        showLoading();
        setTimeout(hideLoading, 2000);
    });
});

// Search highlight
function highlightSearchTerm(term) {
    if (!term) return;
    
    const cells = document.querySelectorAll('.table td');
    cells.forEach(cell => {
        const text = cell.textContent;
        if (text.toLowerCase().includes(term.toLowerCase())) {
            const regex = new RegExp(`(${term})`, 'gi');
            cell.innerHTML = text.replace(regex, '<mark style="background: #fef08a; padding: 2px 4px; border-radius: 4px;">$1</mark>');
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    autoHideAlerts();
    
    // Add smooth transitions to all buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.style.transition = 'all 0.3s ease';
    });
    
    // Add hover effect to cards
    document.querySelectorAll('.card, .card-glass').forEach(card => {
        card.style.transition = 'all 0.3s ease';
    });
});

// Refresh button animation
document.querySelectorAll('[id*="refresh"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const icon = this.querySelector('i, svg');
        if (icon) {
            icon.style.animation = 'spin 1s ease-in-out';
            setTimeout(() => {
                icon.style.animation = '';
            }, 1000);
        }
    });
});

// Escape HTML for security
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Format JSON
function formatJSON(jsonString) {
    try {
        const obj = JSON.parse(jsonString);
        return JSON.stringify(obj, null, 2);
    } catch (e) {
        return jsonString;
    }
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Live search
const searchInputs = document.querySelectorAll('input[name="q"]');
searchInputs.forEach(input => {
    input.addEventListener('input', debounce(function(e) {
        const term = e.target.value;
        if (term.length >= 3) {
            highlightSearchTerm(term);
        }
    }, 500));
});

console.log('🚀 Audit Trail System loaded successfully!');
