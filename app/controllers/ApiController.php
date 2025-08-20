<?php
/**
 * Controlador de API para funciones AJAX
 */

class ApiController extends BaseController {
    
    public function buscarEmpresa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $rfc = $input['rfc'] ?? '';
        
        if (empty($rfc)) {
            $this->json(['error' => 'RFC requerido'], 400);
        }
        
        // Buscar empresa por RFC
        $empresa = $this->db->fetch(
            "SELECT * FROM empresas WHERE rfc = ?",
            [$rfc]
        );
        
        if ($empresa) {
            // Buscar representante principal
            $representante = $this->db->fetch(
                "SELECT * FROM representantes WHERE empresa_id = ? AND es_contacto_principal = 1",
                [$empresa['id']]
            );
            
            $this->json([
                'found' => true,
                'empresa' => $empresa,
                'representante' => $representante
            ]);
        } else {
            $this->json(['found' => false]);
        }
    }
    
    public function buscarInvitado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $telefono = $input['telefono'] ?? '';
        
        if (empty($telefono)) {
            $this->json(['error' => 'Teléfono requerido'], 400);
        }
        
        // Buscar invitado por teléfono
        $invitado = $this->db->fetch(
            "SELECT * FROM invitados WHERE telefono = ?",
            [$telefono]
        );
        
        if ($invitado) {
            $this->json([
                'found' => true,
                'invitado' => $invitado
            ]);
        } else {
            $this->json(['found' => false]);
        }
    }
    
    public function registroEmpresa() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            $this->redirect('');
        }
        
        $eventoSlug = $_POST['evento_slug'] ?? '';
        $rfc = trim($_POST['rfc'] ?? '');
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $puesto = $_POST['puesto'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        
        // Datos de la empresa
        $razonSocial = trim($_POST['razon_social'] ?? '');
        $nombreComercial = trim($_POST['nombre_comercial'] ?? '');
        $direccionFiscal = trim($_POST['direccion_fiscal'] ?? '');
        $direccionComercial = trim($_POST['direccion_comercial'] ?? '');
        $giroComercial = $_POST['giro_comercial'] ?? '';
        $telefonoOficina = trim($_POST['telefono_oficina'] ?? '');
        $aniversario = $_POST['aniversario'] ?? null;
        $numeroAfiliacion = trim($_POST['numero_afiliacion'] ?? '');
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($rfc)) $errors[] = 'RFC requerido';
        if (empty($nombreCompleto)) $errors[] = 'Nombre completo requerido';
        if (empty($email)) $errors[] = 'Email requerido';
        if (empty($puesto)) $errors[] = 'Puesto requerido';
        if (empty($razonSocial)) $errors[] = 'Razón social requerida';
        if (empty($nombreComercial)) $errors[] = 'Nombre comercial requerido';
        if (empty($giroComercial)) $errors[] = 'Giro comercial requerido';
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('registro/empresa/' . $eventoSlug);
        }
        
        // Obtener evento
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE slug = ? AND estado = 'publicado'",
            [$eventoSlug]
        );
        
        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            $this->redirect('');
        }
        
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Insertar o actualizar empresa
            $empresa = $this->db->fetch("SELECT * FROM empresas WHERE rfc = ?", [$rfc]);
            
            if ($empresa) {
                // Actualizar empresa existente
                $this->db->query(
                    "UPDATE empresas SET razon_social = ?, nombre_comercial = ?, direccion_fiscal = ?, 
                     direccion_comercial = ?, telefono_oficina = ?, giro_comercial = ?, numero_afiliacion = ?, updated_at = NOW()
                     WHERE id = ?",
                    [$razonSocial, $nombreComercial, $direccionFiscal, $direccionComercial, 
                     $telefonoOficina, $giroComercial, $numeroAfiliacion, $empresa['id']]
                );
                $empresaId = $empresa['id'];
            } else {
                // Insertar nueva empresa
                $this->db->query(
                    "INSERT INTO empresas (rfc, razon_social, nombre_comercial, direccion_fiscal, 
                     direccion_comercial, telefono_oficina, giro_comercial, numero_afiliacion) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [$rfc, $razonSocial, $nombreComercial, $direccionFiscal, $direccionComercial, 
                     $telefonoOficina, $giroComercial, $numeroAfiliacion]
                );
                $empresaId = $this->db->lastInsertId();
            }
            
            // Insertar o actualizar representante
            $representante = $this->db->fetch(
                "SELECT * FROM representantes WHERE empresa_id = ? AND email = ?",
                [$empresaId, $email]
            );
            
            if ($representante) {
                // Actualizar representante
                $this->db->query(
                    "UPDATE representantes SET nombre_completo = ?, telefono = ?, puesto = ?, updated_at = NOW()
                     WHERE id = ?",
                    [$nombreCompleto, $telefono, $puesto, $representante['id']]
                );
                $representanteId = $representante['id'];
            } else {
                // Insertar nuevo representante
                $this->db->query(
                    "INSERT INTO representantes (empresa_id, nombre_completo, email, telefono, puesto, es_contacto_principal) 
                     VALUES (?, ?, ?, ?, ?, 1)",
                    [$empresaId, $nombreCompleto, $email, $telefono, $puesto]
                );
                $representanteId = $this->db->lastInsertId();
            }
            
            // Verificar si ya está registrado en este evento
            $registroExistente = $this->db->fetch(
                "SELECT * FROM registros_eventos WHERE evento_id = ? AND empresa_id = ? AND representante_id = ?",
                [$evento['id'], $empresaId, $representanteId]
            );
            
            if ($registroExistente) {
                throw new Exception('Ya estás registrado en este evento');
            }
            
            // Generar código único
            $codigoUnico = $this->generateUniqueCode();
            
            // Insertar registro al evento
            $this->db->query(
                "INSERT INTO registros_eventos (evento_id, codigo_unico, tipo_registrante, empresa_id, representante_id, estado) 
                 VALUES (?, ?, 'empresa', ?, ?, 'registrado')",
                [$evento['id'], $codigoUnico, $empresaId, $representanteId]
            );
            
            $this->db->getConnection()->commit();
            
            $_SESSION['success'] = 'Registro exitoso. Recibirás tu boleto por email.';
            $this->redirect('registro/confirmacion/' . $codigoUnico);
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('registro/empresa/' . $eventoSlug);
        }
    }
    
    public function registroInvitado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Método no permitido';
            $this->redirect('');
        }
        
        $eventoSlug = $_POST['evento_slug'] ?? '';
        $telefono = trim($_POST['telefono'] ?? '');
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fechaNacimiento = $_POST['fecha_nacimiento'] ?? null;
        $ocupacion = $_POST['ocupacion'] ?? '';
        $cargoGubernamental = $_POST['cargo_gubernamental'] ?? null;
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($telefono)) $errors[] = 'Teléfono requerido';
        if (empty($nombreCompleto)) $errors[] = 'Nombre completo requerido';
        if (empty($email)) $errors[] = 'Email requerido';
        if (empty($ocupacion)) $errors[] = 'Ocupación requerida';
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode(', ', $errors);
            $this->redirect('registro/invitado/' . $eventoSlug);
        }
        
        // Obtener evento
        $evento = $this->db->fetch(
            "SELECT * FROM eventos WHERE slug = ? AND estado = 'publicado'",
            [$eventoSlug]
        );
        
        if (!$evento) {
            $_SESSION['error'] = 'Evento no encontrado';
            $this->redirect('');
        }
        
        try {
            $this->db->getConnection()->beginTransaction();
            
            // Insertar o actualizar invitado
            $invitado = $this->db->fetch("SELECT * FROM invitados WHERE telefono = ?", [$telefono]);
            
            if ($invitado) {
                // Actualizar invitado existente
                $this->db->query(
                    "UPDATE invitados SET nombre_completo = ?, email = ?, fecha_nacimiento = ?, 
                     ocupacion = ?, cargo_gubernamental = ?, updated_at = NOW() WHERE id = ?",
                    [$nombreCompleto, $email, $fechaNacimiento, $ocupacion, $cargoGubernamental, $invitado['id']]
                );
                $invitadoId = $invitado['id'];
            } else {
                // Insertar nuevo invitado
                $this->db->query(
                    "INSERT INTO invitados (telefono, nombre_completo, email, fecha_nacimiento, ocupacion, cargo_gubernamental) 
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [$telefono, $nombreCompleto, $email, $fechaNacimiento, $ocupacion, $cargoGubernamental]
                );
                $invitadoId = $this->db->lastInsertId();
            }
            
            // Verificar si ya está registrado en este evento
            $registroExistente = $this->db->fetch(
                "SELECT * FROM registros_eventos WHERE evento_id = ? AND invitado_id = ?",
                [$evento['id'], $invitadoId]
            );
            
            if ($registroExistente) {
                throw new Exception('Ya estás registrado en este evento');
            }
            
            // Generar código único
            $codigoUnico = $this->generateUniqueCode();
            
            // Insertar registro al evento
            $this->db->query(
                "INSERT INTO registros_eventos (evento_id, codigo_unico, tipo_registrante, invitado_id, estado) 
                 VALUES (?, ?, 'invitado', ?, 'registrado')",
                [$evento['id'], $codigoUnico, $invitadoId]
            );
            
            $this->db->getConnection()->commit();
            
            $_SESSION['success'] = 'Registro exitoso. Recibirás tu boleto por email.';
            $this->redirect('registro/confirmacion/' . $codigoUnico);
            
        } catch (Exception $e) {
            $this->db->getConnection()->rollback();
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('registro/invitado/' . $eventoSlug);
        }
    }
    
    private function generateUniqueCode() {
        do {
            $codigo = mt_rand(10000000, 99999999); // 8 dígitos
            $exists = $this->db->fetch(
                "SELECT id FROM registros_eventos WHERE codigo_unico = ?",
                [$codigo]
            );
        } while ($exists);
        
        return $codigo;
    }
}
?>