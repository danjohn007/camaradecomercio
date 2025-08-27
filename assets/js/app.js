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
        // Helper function to safely set form field values
        setFieldValue: function(fieldId, value) {
            const element = document.getElementById(fieldId);
            if (!element) {
                return false;
            }
            
            if (element.tagName.toLowerCase() === 'select') {
                // For select elements, check if the value exists as an option
                const option = element.querySelector(`option[value="${value}"]`);
                if (option) {
                    element.value = value;
                    return true;
                } else {
                    // Value not found in select options, skip silently
                    return false;
                }
            } else {
                // For other input types, set the value directly
                element.value = value || '';
                return true;
            }
        },
        
        searchByRFC: function(rfc, eventSlug) {
            if (rfc.length < 12) return;
            
            // Mostrar indicador de carga
            const rfcInput = document.getElementById('rfc');
            const originalPlaceholder = rfcInput.placeholder;
            rfcInput.placeholder = 'Buscando datos...';
            rfcInput.disabled = true;
            
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
                    // Pre-llenar formulario con datos existentes de empresa
                    if (data.empresa) {
                        CANACO.registration.setFieldValue('razon_social', data.empresa.razon_social);
                        CANACO.registration.setFieldValue('nombre_comercial', data.empresa.nombre_comercial);
                        CANACO.registration.setFieldValue('telefono_oficina', data.empresa.telefono_oficina);
                        CANACO.registration.setFieldValue('direccion_fiscal', data.empresa.direccion_fiscal);
                        CANACO.registration.setFieldValue('direccion_comercial', data.empresa.direccion_comercial);
                        CANACO.registration.setFieldValue('giro_comercial', data.empresa.giro_comercial);
                        CANACO.registration.setFieldValue('numero_afiliacion', data.empresa.numero_afiliacion);
                        
                        // Manejar checkbox de consejero_camara si existe en los datos
                        const consejeroCheckbox = document.getElementById('consejero_camara');
                        if (consejeroCheckbox && data.empresa.consejero_camara !== undefined) {
                            consejeroCheckbox.checked = data.empresa.consejero_camara == 1;
                        }
                    }
                    
                    // Pre-llenar datos del representante si existen
                    if (data.representante) {
                        CANACO.registration.setFieldValue('nombre_completo', data.representante.nombre_completo);
                        CANACO.registration.setFieldValue('email', data.representante.email);
                        CANACO.registration.setFieldValue('telefono', data.representante.telefono);
                        CANACO.registration.setFieldValue('puesto', data.representante.puesto);
                    }
                    
                    CANACO.utils.showAlert('✓ Datos encontrados y pre-cargados desde registros anteriores', 'success');
                } else if (rfc.length >= 12) {
                    CANACO.utils.showAlert('ℹ RFC no encontrado en registros anteriores. Puedes continuar con el registro.', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar empresa:', error);
                CANACO.utils.showAlert('Error de conexión al buscar datos. Intenta nuevamente.', 'warning');
            })
            .finally(() => {
                // Restaurar input
                rfcInput.placeholder = originalPlaceholder;
                rfcInput.disabled = false;
            });
        },
        
        searchByPhone: function(telefono, eventSlug) {
            if (telefono.length < 10) return;
            
            // Mostrar indicador de carga
            const phoneInput = document.getElementById('telefono');
            const originalPlaceholder = phoneInput.placeholder;
            phoneInput.placeholder = 'Buscando datos...';
            phoneInput.disabled = true;
            
            fetch(`${CANACO.baseUrl}api/buscar-por-telefono`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ telefono: telefono })
            })
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    if (data.tipo === 'invitado' && data.invitado) {
                        // Mostrar modal para decidir acción si existe la función
                        if (typeof window.showExistingUserModal === 'function') {
                            window.showExistingUserModal(data.invitado);
                        }
                        
                        // Pre-llenar formulario con datos existentes de invitado
                        CANACO.registration.setFieldValue('nombre_completo', data.invitado.nombre_completo);
                        CANACO.registration.setFieldValue('email', data.invitado.email);
                        CANACO.registration.setFieldValue('ocupacion', data.invitado.ocupacion);
                        CANACO.registration.setFieldValue('cargo_gubernamental', data.invitado.cargo_gubernamental);
                        
                        CANACO.utils.showAlert('✓ Datos encontrados y pre-cargados desde registros anteriores', 'success');
                    } else if (data.tipo === 'empresa' && data.representante) {
                        // Pre-llenar con datos del representante de la empresa
                        CANACO.registration.setFieldValue('nombre_completo', data.representante.nombre_completo);
                        CANACO.registration.setFieldValue('email', data.representante.email);
                        CANACO.registration.setFieldValue('puesto', data.representante.puesto || 'Dueño o Representante Legal');
                        
                        // Si hay datos de empresa asociados, precargar todos los campos
                        if (data.empresa) {
                            CANACO.registration.setFieldValue('rfc', data.empresa.rfc);
                            CANACO.registration.setFieldValue('razon_social', data.empresa.razon_social);
                            CANACO.registration.setFieldValue('nombre_comercial', data.empresa.nombre_comercial);
                            CANACO.registration.setFieldValue('direccion_fiscal', data.empresa.direccion_fiscal);
                            CANACO.registration.setFieldValue('direccion_comercial', data.empresa.direccion_comercial);
                            CANACO.registration.setFieldValue('telefono_oficina', data.empresa.telefono_oficina);
                            CANACO.registration.setFieldValue('giro_comercial', data.empresa.giro_comercial);
                            CANACO.registration.setFieldValue('numero_afiliacion', data.empresa.numero_afiliacion);
                            
                            // Manejar checkbox de consejero_camara si existe en los datos
                            const consejeroCheckbox = document.getElementById('consejero_camara');
                            if (consejeroCheckbox && data.empresa.consejero_camara !== undefined) {
                                consejeroCheckbox.checked = data.empresa.consejero_camara == 1;
                            }
                        }
                        
                        CANACO.utils.showAlert('✓ Datos encontrados y pre-cargados desde registros anteriores', 'success');
                    }
                } else if (telefono.length >= 10) {
                    CANACO.utils.showAlert('ℹ Teléfono no encontrado en registros anteriores. Puedes continuar con el registro.', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar por teléfono:', error);
                CANACO.utils.showAlert('Error de conexión al buscar datos. Intenta nuevamente.', 'warning');
            })
            .finally(() => {
                // Restaurar input
                phoneInput.placeholder = originalPlaceholder;
                phoneInput.disabled = false;
            });
        },
        
        searchByEmail: function(email, eventSlug) {
            if (email.length < 5 || !CANACO.validation.validateEmail(email)) return;
            
            // Mostrar indicador de carga
            const emailInput = document.getElementById('email');
            const originalPlaceholder = emailInput.placeholder;
            emailInput.placeholder = 'Buscando datos...';
            emailInput.disabled = true;
            
            fetch(`${CANACO.baseUrl}api/buscar-por-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.found) {
                    if (data.tipo === 'invitado' && data.invitado) {
                        // Pre-llenar formulario con datos de invitado
                        CANACO.registration.setFieldValue('nombre_completo', data.invitado.nombre_completo);
                        CANACO.registration.setFieldValue('telefono', data.invitado.telefono);
                        CANACO.registration.setFieldValue('ocupacion', data.invitado.ocupacion);
                        CANACO.registration.setFieldValue('puesto', data.invitado.ocupacion);
                        CANACO.registration.setFieldValue('cargo_gubernamental', data.invitado.cargo_gubernamental);
                        
                        CANACO.utils.showAlert('✓ Datos encontrados y pre-cargados desde registros anteriores', 'success');
                    } else if (data.tipo === 'empresa' && data.representante) {
                        // Pre-llenar con datos del representante de la empresa
                        CANACO.registration.setFieldValue('nombre_completo', data.representante.nombre_completo);
                        CANACO.registration.setFieldValue('telefono', data.representante.telefono);
                        CANACO.registration.setFieldValue('puesto', data.representante.puesto || 'Dueño o Representante Legal');
                        
                        // Si hay datos de empresa asociados, precargar todos los campos
                        if (data.empresa) {
                            CANACO.registration.setFieldValue('rfc', data.empresa.rfc);
                            CANACO.registration.setFieldValue('razon_social', data.empresa.razon_social);
                            CANACO.registration.setFieldValue('nombre_comercial', data.empresa.nombre_comercial);
                            CANACO.registration.setFieldValue('direccion_fiscal', data.empresa.direccion_fiscal);
                            CANACO.registration.setFieldValue('direccion_comercial', data.empresa.direccion_comercial);
                            CANACO.registration.setFieldValue('telefono_oficina', data.empresa.telefono_oficina);
                            CANACO.registration.setFieldValue('giro_comercial', data.empresa.giro_comercial);
                            CANACO.registration.setFieldValue('numero_afiliacion', data.empresa.numero_afiliacion);
                            
                            // Manejar checkbox de consejero_camara si existe en los datos
                            const consejeroCheckbox = document.getElementById('consejero_camara');
                            if (consejeroCheckbox && data.empresa.consejero_camara !== undefined) {
                                consejeroCheckbox.checked = data.empresa.consejero_camara == 1;
                            }
                        }
                        
                        CANACO.utils.showAlert('✓ Datos encontrados y pre-cargados desde registros anteriores', 'success');
                    }
                } else {
                    CANACO.utils.showAlert('ℹ Email no encontrado en registros anteriores. Puedes continuar con el registro.', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar por email:', error);
                CANACO.utils.showAlert('Error de conexión al buscar datos. Intenta nuevamente.', 'warning');
            })
            .finally(() => {
                // Restaurar input
                emailInput.placeholder = originalPlaceholder;
                emailInput.disabled = false;
            });
        },
        
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
                if (data.found && data.representante) {
                    // Pre-llenar con datos del representante de la empresa
                    CANACO.registration.setFieldValue('nombre_completo', data.representante.nombre_completo);
                    CANACO.registration.setFieldValue('email', data.representante.email);
                    CANACO.registration.setFieldValue('telefono', data.representante.telefono);
                    CANACO.registration.setFieldValue('ocupacion', 'Dueño o Representante Legal');
                    
                    CANACO.utils.showAlert('📋 Datos de empresa encontrados. Complete el registro como invitado con los datos pre-llenados.', 'info');
                } else {
                    CANACO.utils.showAlert('ℹ RFC no encontrado. Puede continuar con el registro como invitado.', 'info');
                }
            })
            .catch(error => {
                console.error('Error al buscar empresa:', error);
                CANACO.utils.showAlert('Error de conexión al buscar datos. Intenta nuevamente.', 'warning');
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
    const rfcSearchInput = document.getElementById('rfc');
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
    
    const phoneSearchInput = document.getElementById('telefono');
    if (phoneSearchInput && phoneSearchInput.dataset.eventSlug) {
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
    
    const emailSearchInput = document.getElementById('email');
    if (emailSearchInput && emailSearchInput.dataset.eventSlug) {
        let timeout;
        emailSearchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const eventSlug = this.dataset.eventSlug;
                if (this.value.length > 5 && CANACO.validation.validateEmail(this.value)) {
                    CANACO.registration.searchByEmail(this.value, eventSlug);
                }
            }, 800);
        });
    }
});

// Exponer funciones globalmente para uso en templates
window.CANACO = CANACO;