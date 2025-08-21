    <?php if (isset($_SESSION['user_id'])): ?>
            </main>
        </div>
    </div>
    <?php else: ?>
    </div>
    <?php endif; ?>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/app.js"></script>
    
    <script>
        // Actualizar estado activo en sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            
            sidebarLinks.forEach(link => {
                if (currentPath.includes(link.getAttribute('href').replace('<?php echo BASE_URL; ?>', '/'))) {
                    link.classList.add('active');
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html>