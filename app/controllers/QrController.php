<?php
/**
 * Controlador de validación QR
 */

class QrController extends BaseController {
    
    public function index() {
        $this->requireAuth();
        
        $this->view('qr/index', [
            'pageTitle' => 'Validación QR'
        ]);
    }
    
    public function validate() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $codigoUnico = trim($input['codigo'] ?? '');
        
        if (empty($codigoUnico)) {
            $this->json(['error' => 'Código QR requerido'], 400);
        }
        
        try {
            // Buscar el registro
            $registro = $this->db->fetch(
                "SELECT re.*, e.titulo as evento_titulo, e.fecha_evento,
                        CASE 
                            WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                            ELSE inv.nombre_completo
                        END as nombre_registrante,
                        CASE 
                            WHEN re.tipo_registrante = 'empresa' THEN rep.email
                            ELSE inv.email
                        END as email_registrante,
                        CASE 
                            WHEN re.tipo_registrante = 'empresa' THEN rep.telefono
                            ELSE inv.telefono
                        END as telefono_registrante
                 FROM registros_eventos re
                 INNER JOIN eventos e ON re.evento_id = e.id
                 LEFT JOIN empresas emp ON re.empresa_id = emp.id
                 LEFT JOIN representantes rep ON re.representante_id = rep.id
                 LEFT JOIN invitados inv ON re.invitado_id = inv.id
                 WHERE re.codigo_unico = ?",
                [$codigoUnico]
            );
            
            if (!$registro) {
                $this->json(['error' => 'Código QR no válido'], 404);
            }
            
            // Verificar si el evento ya pasó
            if (strtotime($registro['fecha_evento']) < time() - (24 * 60 * 60)) { // 24 horas después del evento
                // Permitir validación hasta 24 horas después del evento
            }
            
            $response = [
                'valido' => true,
                'registro' => [
                    'codigo' => $registro['codigo_unico'],
                    'evento' => $registro['evento_titulo'],
                    'fecha_evento' => $registro['fecha_evento'],
                    'nombre' => $registro['nombre_registrante'],
                    'email' => $registro['email_registrante'],
                    'telefono' => $registro['telefono_registrante'],
                    'tipo' => $registro['tipo_registrante'],
                    'estado_actual' => $registro['estado'],
                    'fecha_registro' => $registro['created_at']
                ]
            ];
            
            // Si ya asistió, solo mostrar información
            if ($registro['estado'] === 'asistio') {
                $response['ya_validado'] = true;
                $response['fecha_asistencia'] = $registro['fecha_asistencia'];
                $this->json($response);
            }
            
            // Si está cancelado, no permitir validación
            if ($registro['estado'] === 'cancelado') {
                $this->json(['error' => 'Registro cancelado'], 400);
            }
            
            // Marcar asistencia
            $this->db->query(
                "UPDATE registros_eventos 
                 SET estado = 'asistio', 
                     fecha_asistencia = NOW(), 
                     validado_por = ? 
                 WHERE codigo_unico = ?",
                [$_SESSION['user_id'], $codigoUnico]
            );
            
            // Log de la actividad
            $this->logActivity(
                'validar_qr',
                "QR validado para: {$registro['nombre_registrante']} en evento: {$registro['evento_titulo']}",
                'registros_eventos',
                $registro['id']
            );
            
            $response['validado'] = true;
            $response['fecha_validacion'] = date('Y-m-d H:i:s');
            
            $this->json($response);
            
        } catch (Exception $e) {
            error_log("Error en validación QR: " . $e->getMessage());
            $this->json(['error' => 'Error interno del servidor'], 500);
        }
    }
    
    public function history() {
        $this->requireAuth();
        
        // Obtener filtros
        $evento = $_GET['evento'] ?? '';
        $fecha = $_GET['fecha'] ?? '';
        $tipo = $_GET['tipo'] ?? '';
        $limit = min(100, max(10, intval($_GET['limit'] ?? 50)));
        $page = max(1, intval($_GET['page'] ?? 1));
        $offset = ($page - 1) * $limit;
        
        // Construir consulta
        $sql = "SELECT re.*, e.titulo as evento_titulo, e.fecha_evento,
                       CASE 
                           WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social
                           ELSE inv.nombre_completo
                       END as nombre_registrante,
                       CASE 
                           WHEN re.tipo_registrante = 'empresa' THEN rep.email
                           ELSE inv.email
                       END as email_registrante,
                       u.nombre as validado_por_nombre
                FROM registros_eventos re
                INNER JOIN eventos e ON re.evento_id = e.id
                LEFT JOIN empresas emp ON re.empresa_id = emp.id
                LEFT JOIN representantes rep ON re.representante_id = rep.id
                LEFT JOIN invitados inv ON re.invitado_id = inv.id
                LEFT JOIN usuarios u ON re.validado_por = u.id
                WHERE re.estado = 'asistio'";
        
        $params = [];
        $countParams = [];
        
        // Aplicar filtros
        if (!empty($evento)) {
            $sql .= " AND e.id = ?";
            $params[] = $evento;
            $countParams[] = $evento;
        }
        
        if (!empty($fecha)) {
            $sql .= " AND DATE(re.fecha_asistencia) = ?";
            $params[] = $fecha;
            $countParams[] = $fecha;
        }
        
        if (!empty($tipo)) {
            $sql .= " AND re.tipo_registrante = ?";
            $params[] = $tipo;
            $countParams[] = $tipo;
        }
        
        // Si es gestor, solo ver validaciones de sus eventos
        if ($_SESSION['user_role'] === 'gestor') {
            $sql .= " AND e.usuario_id = ?";
            $params[] = $_SESSION['user_id'];
            $countParams[] = $_SESSION['user_id'];
        }
        
        // Obtener total de registros
        $countSql = str_replace("re.*, e.titulo as evento_titulo, e.fecha_evento, CASE WHEN re.tipo_registrante = 'empresa' THEN emp.razon_social ELSE inv.nombre_completo END as nombre_registrante, CASE WHEN re.tipo_registrante = 'empresa' THEN rep.email ELSE inv.email END as email_registrante, u.nombre as validado_por_nombre", "COUNT(*) as total", $sql);
        $totalRecords = $this->db->fetch($countSql, $countParams)['total'];
        
        // Aplicar paginación y ordenamiento
        $sql .= " ORDER BY re.fecha_asistencia DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $validaciones = $this->db->fetchAll($sql, $params);
        
        // Obtener lista de eventos para el filtro
        $eventosFilter = $this->db->fetchAll(
            "SELECT e.id, e.titulo 
             FROM eventos e 
             INNER JOIN registros_eventos re ON e.id = re.evento_id 
             WHERE re.estado = 'asistio'" . 
             ($_SESSION['user_role'] === 'gestor' ? " AND e.usuario_id = ?" : "") .
             " GROUP BY e.id 
             ORDER BY e.titulo",
            $_SESSION['user_role'] === 'gestor' ? [$_SESSION['user_id']] : []
        );
        
        $this->view('qr/history', [
            'validaciones' => $validaciones,
            'eventosFilter' => $eventosFilter,
            'filters' => [
                'evento' => $evento,
                'fecha' => $fecha,
                'tipo' => $tipo,
                'limit' => $limit,
                'page' => $page
            ],
            'pagination' => [
                'current' => $page,
                'total' => ceil($totalRecords / $limit),
                'records' => $totalRecords
            ]
        ]);
    }
    
    public function stats() {
        $this->requireAuth();
        
        // Estadísticas de validaciones
        $stats = [];
        
        // Total de validaciones hoy
        $stats['hoy'] = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos 
             WHERE estado = 'asistio' AND DATE(fecha_asistencia) = CURDATE()"
        )['total'];
        
        // Total de validaciones esta semana
        $stats['semana'] = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos 
             WHERE estado = 'asistio' AND YEARWEEK(fecha_asistencia) = YEARWEEK(NOW())"
        )['total'];
        
        // Total de validaciones este mes
        $stats['mes'] = $this->db->fetch(
            "SELECT COUNT(*) as total FROM registros_eventos 
             WHERE estado = 'asistio' AND YEAR(fecha_asistencia) = YEAR(NOW()) AND MONTH(fecha_asistencia) = MONTH(NOW())"
        )['total'];
        
        // Eventos con validaciones hoy
        $stats['eventos_hoy'] = $this->db->fetchAll(
            "SELECT e.titulo, COUNT(re.id) as validaciones
             FROM eventos e
             INNER JOIN registros_eventos re ON e.id = re.evento_id
             WHERE re.estado = 'asistio' AND DATE(re.fecha_asistencia) = CURDATE()
             GROUP BY e.id
             ORDER BY validaciones DESC"
        );
        
        $this->json($stats);
    }
}
?>