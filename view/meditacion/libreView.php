<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>⏱️ Meditación Libre</h1>
    <p class="subtitulo">Configura tu sesión y empieza cuando quieras</p>

    <!-- Panel de configuración -->
    <div id="panel-config" class="panel-sesion">
        <h2>Configuración</h2>

        <div class="formularios">
            <label>Duración:</label>
            <div class="grupo-botones-dur">
                <button type="button" class="botones btn-dur activo" data-min="5">5 min</button>
                <button type="button" class="botones btn-dur" data-min="10">10 min</button>
                <button type="button" class="botones btn-dur" data-min="15">15 min</button>
                <button type="button" class="botones btn-dur" data-min="20">20 min</button>
                <button type="button" class="botones btn-dur" data-min="30">30 min</button>
                <input type="number" id="min-custom" min="1" max="180" placeholder="personalizado">
            </div>

            <label for="con-gong">Gong inteligente:</label>
            <div>
                <input type="checkbox" id="con-gong" checked>
                <span>Suena al 25%, 50%, 75% y al finalizar</span>
            </div>
        </div>

        <div class="dashboardActions">
            <button class="botones botones-primario" onclick="prepararSesion()">Preparar sesión ▶</button>
            <button class="botones" onclick="history.back()">← Volver</button>
        </div>
    </div>

    <!-- Panel del temporizador -->
    <div id="panel-timer" style="display:none;" class="panel-sesion panel-timer">
        <div class="circulo-timer">
            <div class="timer-display" id="timer-display">00:00</div>
            <div class="timer-label" id="timer-label">Respira y céntrate</div>
        </div>

        <div class="dashboardActions">
            <button id="btn-iniciar" class="botones botones-primario" onclick="toggleTimer()">▶ Iniciar</button>
            <button id="btn-pausar" class="botones" onclick="toggleTimer()" style="display:none;">⏸ Pausar</button>
            <button id="btn-reiniciar" class="botones" onclick="reiniciarTimer()" style="display:none;">↺ Reiniciar</button>
        </div>

        <!-- Formulario para guardar sesión al completar -->
        <div id="bloque-guardar" style="display:none;">
            <div class="alerta alerta-ok">🎉 ¡Sesión completada! ¿Quieres guardarla?</div>
            <form id="guardarSesionForm" method="POST" action="/<?php echo $projectName; ?>/controller/SesionController.php">
                <input type="hidden" name="action" value="registrar">
                <input type="hidden" name="tipo" value="libre">
                <input type="hidden" name="id_meditacion" value="">
                <input type="hidden" id="input-duracion" name="duracion_min" value="">
                <input type="hidden" id="input-gong" name="con_gong" value="0">
            </form>
            <div class="dashboardActions">
                <button type="submit" form="guardarSesionForm" class="botones botones-primario">✅ Guardar sesión</button>
                <button class="botones" onclick="window.location.href='/<?php echo $projectName; ?>/index.php'">Saltar</button>
            </div>
        </div>
    </div>
</main>

<script>
var duracion = 5, total = 0, seg = 0, interval = null, running = false;

document.querySelectorAll('.btn-dur').forEach(function(btn) {
    btn.addEventListener('click', function() {
        duracion = parseInt(this.dataset.min);
        document.querySelectorAll('.btn-dur').forEach(b => b.classList.remove('activo'));
        this.classList.add('activo');
    });
});

function prepararSesion() {
    var c = parseInt(document.getElementById('min-custom').value);
    if (c && c > 0) duracion = c;
    total = duracion * 60;
    seg   = total;
    actualizarDisplay();
    document.getElementById('panel-config').style.display = 'none';
    document.getElementById('panel-timer').style.display  = 'block';
    document.getElementById('input-duracion').value       = duracion;
    document.getElementById('input-gong').value           = document.getElementById('con-gong').checked ? 1 : 0;
}

function toggleTimer() {
    if (running) {
        clearInterval(interval);
        running = false;
        document.getElementById('btn-iniciar').style.display = 'inline-block';
        document.getElementById('btn-iniciar').textContent   = '▶ Continuar';
        document.getElementById('btn-pausar').style.display  = 'none';
    } else {
        running = true;
        document.getElementById('btn-iniciar').style.display  = 'none';
        document.getElementById('btn-pausar').style.display   = 'inline-block';
        document.getElementById('btn-reiniciar').style.display= 'inline-block';
        interval = setInterval(function() {
            seg--;
            actualizarDisplay();
            if (seg <= 0) {
                clearInterval(interval);
                running = false;
                document.getElementById('timer-label').textContent = '¡Completado!';
                document.getElementById('bloque-guardar').style.display = 'block';
            }
        }, 1000);
    }
}

function reiniciarTimer() {
    clearInterval(interval);
    running = false;
    seg = total;
    actualizarDisplay();
    document.getElementById('timer-label').textContent    = 'Respira y céntrate';
    document.getElementById('btn-iniciar').style.display  = 'inline-block';
    document.getElementById('btn-iniciar').textContent    = '▶ Iniciar';
    document.getElementById('btn-pausar').style.display   = 'none';
    document.getElementById('bloque-guardar').style.display = 'none';
}

function actualizarDisplay() {
    var m = Math.floor(seg / 60), s = seg % 60;
    document.getElementById('timer-display').textContent =
        (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
}

actualizarDisplay();
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
