/**
 * JavaScript personalizado para Sistema de Eventos CANACO
 */

// Configuración global
const CANACO = {
    baseUrl: document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '/',
    
    // Utilidades
    utils: {
        formatDate: function(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-MX', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
        
        formatCurrency: function(amount) {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(amount);
        },
        
        showAlert: function(message, type = 'info') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            const alertContainer = document.getElementById('alert-container') || document.querySelector('.container-fluid');
            alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 150);
                }
            }, 5000);
        },
        
        confirmAction: function(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
    },
    
    // Funciones para eventos
    events: {
        delete: function(eventId, eventTitle) {
            CANACO.utils.confirmAction(
                `¿Estás seguro de que quieres eliminar el evento "${eventTitle}"?`,
                function() {
                    fetch(`${CANACO.baseUrl}eventos/eliminar/${eventId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            CANACO.utils.showAlert('Evento eliminado correctamente', 'success');
                            // Recargar la página después de 1 segundo
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            CANACO.utils.showAlert(data.error || 'Error al eliminar el evento', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        CANACO.utils.showAlert('Error de conexión', 'danger');
                    });
                }
            );
        },
        
        toggleStatus: function(eventId, newStatus) {
            const statusLabels = {
                'borrador': 'borrador',
                'publicado': 'publicado',
                'cerrado': 'cerrado',
                'cancelado': 'cancelado'
            };
            
            CANACO.utils.confirmAction(
                `¿Cambiar el estado del evento a "${statusLabels[newStatus]}"?`,
                function() {
                    fetch(`${CANACO.baseUrl}eventos/cambiar-estado/${eventId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ estado: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            CANACO.utils.showAlert('Estado actualizado correctamente', 'success');
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            CANACO.utils.showAlert(data.error || 'Error al actualizar el estado', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        CANACO.utils.showAlert('Error de conexión', 'danger');
                    });
                }
            );
        }
    },
    
    // Funciones para registro público
    registration: {
        searchByRFC: function(rfc, eventSlug) {
            if (rfc.length < 12) return;
            
            fetch(`${CANACO.baseUrl}api/buscar-empresa`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ rfc: rfc })
            })
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    // Pre-llenar formulario con datos existentes
                    document.getElementById('razon_social').value = data.empresa.razon_social || '';
                    document.getElementById('nombre_comercial').value = data.empresa.nombre_comercial || '';
                    document.getElementById('telefono_oficina').value = data.empresa.telefono_oficina || '';
                    
                    if (data.representante) {
                        document.getElementById('nombre_completo').value = data.representante.nombre_completo || '';
                        document.getElementById('email').value = data.representante.email || '';
                        document.getElementById('telefono').value = data.representante.telefono || '';
                        document.getElementById('puesto').value = data.representante.puesto || '';
                    }
                    
                    CANACO.utils.showAlert('Datos pre-cargados desde registros anteriores', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar empresa:', error);
            });
        },
        
        searchByPhone: function(telefono, eventSlug) {
            if (telefono.length < 10) return;
            
            fetch(`${CANACO.baseUrl}api/buscar-invitado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ telefono: telefono })
            })
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    // Pre-llenar formulario con datos existentes
                    document.getElementById('nombre_completo').value = data.invitado.nombre_completo || '';
                    document.getElementById('email').value = data.invitado.email || '';
                    document.getElementById('fecha_nacimiento').value = data.invitado.fecha_nacimiento || '';
                    document.getElementById('ocupacion').value = data.invitado.ocupacion || '';
                    document.getElementById('cargo_gubernamental').value = data.invitado.cargo_gubernamental || '';
                    
                    CANACO.utils.showAlert('Datos pre-cargados desde registros anteriores', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar invitado:', error);
            });
        }
    },
    
    // Funciones para validación
    validation: {
        validateRFC: function(rfc) {
            // Validación básica de RFC mexicano
            const rfcPattern = /^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/;
            return rfcPattern.test(rfc.toUpperCase());
        },
        
        validateEmail: function(email) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(email);
        },
        
        validatePhone: function(phone) {
            // Validación básica de teléfono mexicano
            const phonePattern = /^[0-9]{10}$/;
            return phonePattern.test(phone.replace(/\D/g, ''));
        }
    }
};

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    
    // Actualizar enlaces activos en sidebar
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
    
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.replace(CANACO.baseUrl, ''))) {
            link.classList.add('active');
        }
    });
    
    // Auto-hide alerts
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
    
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Validación de formularios en tiempo real
    const forms = document.querySelectorAll('form[data-validate="true"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // RFC input formatting
    const rfcInputs = document.querySelectorAll('input[name="rfc"]');
    rfcInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        
        input.addEventListener('blur', function() {
            if (this.value && !CANACO.validation.validateRFC(this.value)) {
                this.classList.add('is-invalid');
                let feedback = this.parentNode.querySelector('.invalid-feedback');
                if (!feedback) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    this.parentNode.appendChild(feedback);
                }
                feedback.textContent = 'RFC inválido';
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    // Phone input formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    });
    
    // Auto-search functionality
    const rfcSearchInput = document.getElementById('rfc_search');
    if (rfcSearchInput) {
        let timeout;
        rfcSearchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const eventSlug = this.dataset.eventSlug;
                if (this.value.length >= 12) {
                    CANACO.registration.searchByRFC(this.value, eventSlug);
                }
            }, 500);
        });
    }
    
    const phoneSearchInput = document.getElementById('telefono_search');
    if (phoneSearchInput) {
        let timeout;
        phoneSearchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const eventSlug = this.dataset.eventSlug;
                if (this.value.length >= 10) {
                    CANACO.registration.searchByPhone(this.value, eventSlug);
                }
            }, 500);
        });
    }
});

// Exponer funciones globalmente para uso en templates
window.CANACO = CANACO;