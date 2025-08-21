-- Datos de ejemplo para la tabla eventos
-- Este archivo contiene registros de ejemplo para la tabla eventos
-- usando usuario_id existentes para evitar errores de clave foránea

INSERT INTO eventos (
    titulo, 
    descripcion, 
    fecha_evento, 
    ubicacion, 
    cupo_maximo, 
    costo, 
    tipo_publico, 
    estado, 
    slug, 
    usuario_id
) VALUES 
(
    'Conferencia de Tecnología e Innovación 2024',
    'Evento especializado en las últimas tendencias tecnológicas y oportunidades de innovación para empresas del sector.',
    '2024-12-20 09:00:00',
    'Centro de Convenciones Querétaro, Av. de la Luz 1',
    200,
    750.00,
    'empresas',
    'publicado',
    'conferencia-tecnologia-innovacion-2024',
    1
),
(
    'Expo Negocios Bajío 2024',
    'Exposición de negocios que reúne a empresarios y emprendedores de la región del Bajío para generar nuevas oportunidades comerciales.',
    '2024-12-25 10:00:00',
    'Centro Expositor Querétaro, Blvd. Bernardo Quintana 4100',
    500,
    1200.00,
    'todos',
    'publicado',
    'expo-negocios-bajio-2024',
    1
),
(
    'Seminario de Marketing Digital',
    'Capacitación intensiva sobre estrategias de marketing digital y comercio electrónico para pequeñas y medianas empresas.',
    '2025-01-15 14:00:00',
    'Hotel Real de Minas, Av. Constituyentes 124',
    80,
    450.00,
    'empresas',
    'borrador',
    'seminario-marketing-digital',
    2
),
(
    'Workshop: Inteligencia Artificial para Empresas',
    'Taller práctico sobre implementación de herramientas de inteligencia artificial en procesos empresariales.',
    '2025-01-20 09:30:00',
    'Centro de Innovación Tecnológica, Av. del Parque 456',
    150,
    900.00,
    'empresas',
    'publicado',
    'workshop-ia-empresas',
    2
),
(
    'Foro de Sustentabilidad Empresarial',
    'Encuentro para discutir las mejores prácticas en sustentabilidad y responsabilidad social empresarial.',
    '2025-02-05 08:00:00',
    'Auditorio de la Cámara de Comercio, Calle 5 de Mayo 120',
    300,
    0.00,
    'todos',
    'publicado',
    'foro-sustentabilidad-empresarial',
    1
),
(
    'Networking Empresarial Mensual',
    'Sesión de networking para promover vínculos comerciales entre empresarios de diferentes sectores.',
    '2025-02-10 18:00:00',
    'Restaurante Business Club, Plaza de Armas 89',
    100,
    350.00,
    'empresas',
    'publicado',
    'networking-empresarial-febrero',
    2
),
(
    'Capacitación en Finanzas Corporativas',
    'Curso especializado en gestión financiera, análisis de inversiones y estrategias de financiamiento empresarial.',
    '2025-02-18 09:00:00',
    'Centro de Capacitación Empresarial, Av. Universidad 234',
    60,
    650.00,
    'empresas',
    'borrador',
    'capacitacion-finanzas-corporativas',
    1
),
(
    'Congreso de Emprendimiento Juvenil',
    'Evento dirigido a jóvenes emprendedores con conferencias magistrales y talleres de desarrollo empresarial.',
    '2025-03-01 10:00:00',
    'Universidad Tecnológica de Querétaro, Av. Pie de la Cuesta 2501',
    400,
    200.00,
    'invitados',
    'publicado',
    'congreso-emprendimiento-juvenil',
    2
);