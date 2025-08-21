-- Test data to reproduce the auto-preload issue
-- RFC: RARD7909214H6
-- Phone: 4424865389

-- Insert test empresa
INSERT INTO `empresas` (`rfc`, `razon_social`, `nombre_comercial`, `direccion_fiscal`, `direccion_comercial`, `telefono_oficina`, `giro_comercial`, `numero_afiliacion`) VALUES
('RARD7909214H6', 'Empresa Test RARD S.A. de C.V.', 'Test Company RARD', 'Av. Test Fiscal 123, Querétaro, Qro.', 'Av. Test Comercial 456, Querétaro, Qro.', '442-555-0123', 'Servicios', 'AF12345');

-- Get the empresa_id (will be the last inserted ID, assuming it's ID 3 or 4 depending on existing data)
-- Insert test representante for the empresa
INSERT INTO `representantes` (`empresa_id`, `nombre_completo`, `email`, `telefono`, `puesto`, `es_contacto_principal`) VALUES
((SELECT id FROM empresas WHERE rfc = 'RARD7909214H6'), 'Roberto Rodríguez Test', 'roberto.test@example.com', '442-555-0101', 'Director General', 1);

-- Insert test invitado
INSERT INTO `invitados` (`telefono`, `nombre_completo`, `email`, `fecha_nacimiento`, `ocupacion`, `cargo_gubernamental`) VALUES
('4424865389', 'Maria Test Invitada', 'maria.test@example.com', '1980-05-15', 'Empresaria', 'No aplica');