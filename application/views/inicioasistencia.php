<main class="page-content">
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-clock"></i> Asistencia</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inicio</li>
            </ol>
        </nav>

        <!-- Floating Widget -->
        <div class="floating-widget border">
            <div class="floating-widget-header">
                Marcaje Web
            </div>
            <div class="floating-widget-body">
                <div id="timer">
                    <span class="timer-icon">⏱️</span> 00:00:00
                </div>
            </div>
            <div class="floating-widget-footer">
                <button class="btn btn-success btn-sm" id="marcarEntrada">Marcar Entrada</button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#masModal">Más</button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="masModal" tabindex="-1" aria-labelledby="masModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="masModalLabel">Marcaje Web</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Última Marca:</strong> <span id="ultimaMarca">-</span></p>
                        <p><strong>Turno de Hoy:</strong> <span id="turnoHoy">-</span></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success">Marcar Entrada</button>
                        <button class="btn btn-danger">Marcar Salida</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script>
        // Timer logic
        let timer = document.getElementById('timer');
        let seconds = 0;
        let timerInterval;

        function formatTime(secs) {
            const hrs = Math.floor(secs / 3600).toString().padStart(2, '0');
            const mins = Math.floor((secs % 3600) / 60).toString().padStart(2, '0');
            const sec = (secs % 60).toString().padStart(2, '0');
            return `${hrs}:${mins}:${sec}`;
        }

        document.getElementById('marcarEntrada').addEventListener('click', () => {
            if (!timerInterval) {
                timerInterval = setInterval(() => {
                    seconds++;
                    timer.innerHTML = `<span class="timer-icon">⏱️</span> ${formatTime(seconds)}`;
                }, 1000);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const modalBody = document.querySelector('#masModal .modal-body');
            const empleadoId = '<?= $this->session->userdata("empleado_id"); ?>'; // ID del empleado

            function registrarAsistencia(tipo) {
                fetch('inicioasistencia/registrar_asistencia', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ tipo_marcaje: tipo })
                })
                .then(response => response.json())
                .then(data => {
                    // Actualiza el modal con los datos
                    modalBody.innerHTML = `
                        <p><strong>Última Marca:</strong> ${data.hora_entrada || data.hora_salida || '-'}</p>
                        <p><strong>Turno de Hoy:</strong> ${data.turno_id || 'N/A'}</p>
                    `;
                })
                .catch(error => console.error('Error:', error));
            }

            document.getElementById('marcarEntrada').addEventListener('click', () => {
                registrarAsistencia('hora_entrada');
            });

            document.querySelector('.btn-danger').addEventListener('click', () => {
                registrarAsistencia('hora_salida');
            });
        });

</script>