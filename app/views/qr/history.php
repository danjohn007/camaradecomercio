<?php $pageTitle = 'Historial de Validaciones QR'; ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-history me-2 text-canaco"></i>
        Historial de Validaciones QR
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <a href="<?php echo BASE_URL; ?>qr" class="btn btn-outline-secondary">
                <i class="fas fa-qrcode"></i> Escáner QR
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="evento" class="form-label">Evento</label>
                <select class="form-select" name="evento" id="evento">
                    <option value="">Todos los eventos</option>
                    <?php foreach ($eventosFilter as $eventoOpt): ?>
                    <option value="<?php echo $eventoOpt['id']; ?>" <?php echo $filters['evento'] == $eventoOpt['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($eventoOpt['titulo']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="fecha" class="form-label">Fecha de Validación</label>
                <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo htmlspecialchars($filters['fecha']); ?>">
            </div>
            <div class="col-md-2">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-select" name="tipo" id="tipo">
                    <option value="">Todos</option>
                    <option value="empresa" <?php echo $filters['tipo'] === 'empresa' ? 'selected' : ''; ?>>Empresas</option>
                    <option value="invitado" <?php echo $filters['tipo'] === 'invitado' ? 'selected' : ''; ?>>Invitados</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="limit" class="form-label">Mostrar</label>
                <select class="form-select" name="limit" id="limit">
                    <option value="10" <?php echo $filters['limit'] == 10 ? 'selected' : ''; ?>>10</option>
                    <option value="25" <?php echo $filters['limit'] == 25 ? 'selected' : ''; ?>>25</option>
                    <option value="50" <?php echo $filters['limit'] == 50 ? 'selected' : ''; ?>>50</option>
                    <option value="100" <?php echo $filters['limit'] == 100 ? 'selected' : ''; ?>>100</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-canaco">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="<?php echo BASE_URL; ?>qr/historial" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de validaciones -->
<?php if (empty($validaciones)): ?>
<div class="text-center py-5">
    <i class="fas fa-search text-muted" style="font-size: 5rem;"></i>
    <h3 class="mt-3 text-muted">No hay validaciones</h3>
    <p class="text-muted">No se encontraron validaciones con los filtros aplicados.</p>
    <a href="<?php echo BASE_URL; ?>qr" class="btn btn-canaco">
        <i class="fas fa-qrcode"></i> Ir al Escáner QR
    </a>
</div>
<?php else: ?>

<!-- Información de paginación -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="text-muted">
        Mostrando <?php echo count($validaciones); ?> de <?php echo number_format($pagination['records']); ?> validaciones
    </div>
    <div class="text-muted">
        Página <?php echo $pagination['current']; ?> de <?php echo $pagination['total']; ?>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Asistente</th>
                    <th>Evento</th>
                    <th>Tipo</th>
                    <th>Validado Por</th>
                    <th>Fecha/Hora Validación</th>
                    <th>Fecha Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($validaciones as $validacion): ?>
                <tr>
                    <td>
                        <code class="bg-light text-dark px-2 py-1 rounded"><?php echo htmlspecialchars($validacion['codigo_unico']); ?></code>
                    </td>
                    <td>
                        <div>
                            <h6 class="mb-0" style="font-size: 0.875rem;">
                                <?php echo htmlspecialchars($validacion['nombre_registrante']); ?>
                            </h6>
                            <small class="text-muted">
                                <?php echo htmlspecialchars($validacion['email_registrante']); ?>
                            </small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="fw-medium" style="font-size: 0.875rem;">
                                <?php echo htmlspecialchars($validacion['evento_titulo']); ?>
                            </span>
                            <small class="text-muted d-block">
                                <?php echo date('d/m/Y H:i', strtotime($validacion['fecha_evento'])); ?>
                            </small>
                        </div>
                    </td>
                    <td>
                        <?php if ($validacion['tipo_registrante'] === 'empresa'): ?>
                            <span class="badge bg-primary">
                                <i class="fas fa-building me-1"></i>Empresa
                            </span>
                        <?php else: ?>
                            <span class="badge bg-info">
                                <i class="fas fa-user me-1"></i>Invitado
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <span class="fw-medium" style="font-size: 0.875rem;">
                                <?php echo htmlspecialchars($validacion['validado_por_nombre'] ?? 'Sistema'); ?>
                            </span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="fw-medium text-success">
                                <?php echo date('d/m/Y', strtotime($validacion['fecha_asistencia'])); ?>
                            </span>
                            <small class="text-muted d-block">
                                <?php echo date('H:i:s', strtotime($validacion['fecha_asistencia'])); ?>
                            </small>
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">
                            <?php echo date('d/m/Y H:i', strtotime($validacion['created_at'])); ?>
                        </small>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Paginación -->
<?php if ($pagination['total'] > 1): ?>
<nav aria-label="Paginación" class="mt-4">
    <ul class="pagination justify-content-center">
        <!-- Anterior -->
        <?php if ($pagination['current'] > 1): ?>
        <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] - 1])); ?>">
                <i class="fas fa-chevron-left"></i> Anterior
            </a>
        </li>
        <?php else: ?>
        <li class="page-item disabled">
            <span class="page-link"><i class="fas fa-chevron-left"></i> Anterior</span>
        </li>
        <?php endif; ?>
        
        <!-- Páginas -->
        <?php 
        $start = max(1, $pagination['current'] - 2);
        $end = min($pagination['total'], $pagination['current'] + 2);
        
        if ($start > 1): ?>
        <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($filters, ['page' => 1])); ?>">1</a>
        </li>
        <?php if ($start > 2): ?>
        <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>
        <?php endif; ?>
        
        <?php for ($i = $start; $i <= $end; $i++): ?>
        <li class="page-item <?php echo $i == $pagination['current'] ? 'active' : ''; ?>">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>"><?php echo $i; ?></a>
        </li>
        <?php endfor; ?>
        
        <?php if ($end < $pagination['total']): ?>
        <?php if ($end < $pagination['total'] - 1): ?>
        <li class="page-item disabled"><span class="page-link">...</span></li>
        <?php endif; ?>
        <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['total']])); ?>"><?php echo $pagination['total']; ?></a>
        </li>
        <?php endif; ?>
        
        <!-- Siguiente -->
        <?php if ($pagination['current'] < $pagination['total']): ?>
        <li class="page-item">
            <a class="page-link" href="?<?php echo http_build_query(array_merge($filters, ['page' => $pagination['current'] + 1])); ?>">
                Siguiente <i class="fas fa-chevron-right"></i>
            </a>
        </li>
        <?php else: ?>
        <li class="page-item disabled">
            <span class="page-link">Siguiente <i class="fas fa-chevron-right"></i></span>
        </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

<?php endif; ?>

<script>
// Auto-submit form cuando cambian los filtros selectores
document.addEventListener('DOMContentLoaded', function() {
    const selects = ['evento', 'tipo', 'limit'];
    
    selects.forEach(selectId => {
        const select = document.getElementById(selectId);
        if (select) {
            select.addEventListener('change', function() {
                // Resetear página a 1 cuando cambian los filtros
                const form = this.closest('form');
                const pageInput = form.querySelector('input[name="page"]');
                if (pageInput) {
                    pageInput.value = '1';
                } else {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'page';
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);
                }
                form.submit();
            });
        }
    });
});
</script>

<style>
code {
    font-size: 0.875rem;
}

.table th {
    background-color: rgba(74, 124, 89, 0.1);
    border-top: none;
    font-weight: 600;
    color: var(--canaco-green);
}

.pagination .page-link {
    color: var(--canaco-green);
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background-color: var(--canaco-green);
    border-color: var(--canaco-green);
}

.pagination .page-link:hover {
    color: var(--canaco-dark-green);
    background-color: rgba(74, 124, 89, 0.1);
    border-color: #dee2e6;
}
</style>