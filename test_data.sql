-- Test data for preload functionality
-- Adding specific RFC and phone mentioned in the problem statement
-- Run this after the main schema.sql to add test data

-- Insert test company with the specific RFC RARD7909214H6
INSERT INTO empresas (rfc, razon_social, nombre_comercial, direccion_fiscal, direccion_comercial, telefono_oficina, giro_comercial) VALUES
('RARD7909214H6', 'Tecnología Avanzada RARD S.A. de C.V.', 'RARD Tech', 'Av. Constituyentes 123, Col. Centro, 76000 Querétaro, Qro.', 'Av. Constituyentes 123, Col. Centro, 76000 Querétaro, Qro.', '442-555-0123', 'Tecnología')
ON DUPLICATE KEY UPDATE 
razon_social = VALUES(razon_social),
nombre_comercial = VALUES(nombre_comercial),
direccion_fiscal = VALUES(direccion_fiscal),
telefono_oficina = VALUES(telefono_oficina),
giro_comercial = VALUES(giro_comercial);

-- Insert representative for the test company
INSERT INTO representantes (empresa_id, nombre_completo, email, telefono, puesto, es_contacto_principal) VALUES
((SELECT id FROM empresas WHERE rfc = 'RARD7909214H6'), 'Roberto Alonzo Ruiz Díaz', 'roberto.ruiz@rardtech.com', '442-555-0124', 'Director General', 1)
ON DUPLICATE KEY UPDATE 
nombre_completo = VALUES(nombre_completo),
email = VALUES(email),
telefono = VALUES(telefono),
puesto = VALUES(puesto);

-- Insert test guest with the specific phone 4424865389
INSERT INTO invitados (telefono, nombre_completo, email, fecha_nacimiento, ocupacion) VALUES
('4424865389', 'María Fernanda González Torres', 'mfernanda.gonzalez@gmail.com', '1988-05-20', 'Empresaria')
ON DUPLICATE KEY UPDATE 
nombre_completo = VALUES(nombre_completo),
email = VALUES(email),
fecha_nacimiento = VALUES(fecha_nacimiento),
ocupacion = VALUES(ocupacion);

-- Verify the data was inserted correctly
SELECT 'Test RFC Company:' as info;
SELECT rfc, razon_social, nombre_comercial FROM empresas WHERE rfc = 'RARD7909214H6';

SELECT 'Test RFC Representative:' as info;
SELECT r.nombre_completo, r.email, r.telefono, r.puesto 
FROM representantes r 
JOIN empresas e ON r.empresa_id = e.id 
WHERE e.rfc = 'RARD7909214H6' AND r.es_contacto_principal = 1;

SELECT 'Test Phone Guest:' as info;
SELECT telefono, nombre_completo, email, ocupacion FROM invitados WHERE telefono = '4424865389';