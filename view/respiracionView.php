<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🌬️ Ejercicios de Respiración</h1>
    <p class="subtitulo">Técnicas probadas para calmar la mente en segundos</p>

    <?php if (empty($respiraciones)): ?>
        <p class="texto-vacio">Aún no hay técnicas disponibles.</p>
    <?php else: ?>
    <div class="tarjetas-grid">
        <?php foreach ($respiraciones as $resp): ?>
        <div class="tarjeta-resp">
            <div class="tarjeta-cabecera">🌬️ <?php echo htmlspecialchars($resp->getNombre()); ?></div>
            <div class="tarjeta-cuerpo">
                <p><?php echo htmlspecialchars($resp->getDescripcion() ?? ''); ?></p>

                <div class="fases-grid">
                    <div class="fase"><span class="fase-val"><?php echo $resp->getInhalaSeg(); ?>s</span><span class="fase-label">Inhala</span></div>
                    <div class="fase"><span class="fase-val"><?php echo $resp->getRetieneSeg(); ?>s</span><span class="fase-label">Retén</span></div>
                    <div class="fase"><span class="fase-val"><?php echo $resp->getExhalaSeg(); ?>s</span><span class="fase-label">Exhala</span></div>
                    <div class="fase"><span class="fase-val"><?php echo $resp->getCiclos(); ?></span><span class="fase-label">Ciclos</span></div>
                </div>

                <button type="button" class="botones botones-primario" style="width:100%;"
                        onclick="abrirEjercicio(
                            '<?php echo addslashes($resp->getNombre()); ?>',
                            <?php echo $resp->getInhalaSeg(); ?>,
                            <?php echo $resp->getRetieneSeg(); ?>,
                            <?php echo $resp->getExhalaSeg(); ?>,
                            <?php echo $resp->getRetiene2Seg(); ?>,
                            <?php echo $resp->getCiclos(); ?>)">
                    ▶ Iniciar
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>

    <!-- Panel de ejercicio activo -->
    <div id="panel-respiracion" style="display:none;" class="panel-sesion panel-timer">
        <h2 id="resp-titulo"></h2>
        <p class="subtitulo" id="resp-info"></p>
        <p>Ciclo <strong id="ciclo-actual">0</strong> de <strong id="ciclo-total"></strong></p>

        <div id="circulo-resp" class="circulo-resp">
            <span id="resp-texto">Preparado</span>
        </div>

        <div class="dashboardActions">
            <button id="btn-resp-iniciar" class="botones botones-primario" onclick="iniciarRespiracion()">▶ Iniciar</button>
            <button id="btn-resp-detener" class="botones botones-peligro" onclick="detenerRespiracion()" style="display:none;">⏹ Detener</button>
        </div>

        <div id="resp-completado" style="display:none;" class="alerta alerta-ok">🎉 ¡Ejercicio completado!</div>

        <div class="dashboardActions">
            <button class="botones" onclick="cerrarEjercicio()">Cerrar</button>
        </div>
    </div>
</main>

<script>
var rActual = {}, rTimeouts = [], rRunning = false, rRegistrada = false;

function abrirEjercicio(nombre, inhala, retiene, exhala, retiene2, ciclos) {
    rActual = {nombre, inhala, retiene, exhala, retiene2, ciclos};
    rRegistrada = false;
    document.getElementById('resp-titulo').textContent  = nombre;
    document.getElementById('resp-info').textContent    = 'Inhala ' + inhala + 's · Retén ' + retiene + 's · Exhala ' + exhala + 's';
    document.getElementById('ciclo-actual').textContent = 0;
    document.getElementById('ciclo-total').textContent  = ciclos;
    document.getElementById('resp-texto').textContent   = 'Preparado';
    document.getElementById('circulo-resp').style.transform = '';
    document.getElementById('resp-completado').style.display = 'none';
    document.getElementById('btn-resp-iniciar').style.display = 'inline-block';
    document.getElementById('btn-resp-detener').style.display = 'none';
    document.getElementById('panel-respiracion').style.display = 'block';
    document.getElementById('panel-respiracion').scrollIntoView({behavior:'smooth'});
}

function cerrarEjercicio() {
    detenerRespiracion();
    document.getElementById('panel-respiracion').style.display = 'none';
}

function detenerRespiracion() {
    rTimeouts.forEach(clearTimeout);
    rTimeouts = [];
    rRunning  = false;
    document.getElementById('btn-resp-iniciar').style.display = 'inline-block';
    document.getElementById('btn-resp-detener').style.display = 'none';
}

function registrarSesionRespiracion() {
    if (rRegistrada) return;
    var totalSeg = (rActual.inhala + rActual.retiene + rActual.exhala + rActual.retiene2) * rActual.ciclos;
    var totalMin = Math.max(1, Math.round(totalSeg / 60));
    var datos = new URLSearchParams();
    datos.append('action', 'registrar');
    datos.append('tipo', 'respiracion');
    datos.append('duracion_min', totalMin);
    datos.append('id_meditacion', '');
    datos.append('con_gong', '0');
    fetch('/<?php echo $projectName; ?>/controller/SesionController.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: datos.toString(),
        credentials: 'same-origin'
    }).then(function() { rRegistrada = true; });
}

function iniciarRespiracion() {
    registrarSesionRespiracion();
    document.getElementById('btn-resp-iniciar').style.display = 'none';
    document.getElementById('btn-resp-detener').style.display = 'inline-block';
    rRunning = true;
    var ciclo = 0;

    function hacerFase(label, escala, durSeg, cb) {
        if (!durSeg || durSeg <= 0) { cb(); return; }
        if (!rRunning) return;
        document.getElementById('resp-texto').textContent = label;
        var circ = document.getElementById('circulo-resp');
        circ.style.transition = 'transform ' + durSeg + 's ease-in-out';
        circ.style.transform  = escala;
        var t = setTimeout(cb, durSeg * 1000);
        rTimeouts.push(t);
    }

    function cicloSiguiente() {
        if (!rRunning) return;
        hacerFase('Inhala…', 'scale(1.45)', rActual.inhala, function() {
            hacerFase('Retén…', 'scale(1.45)', rActual.retiene, function() {
                hacerFase('Exhala…', 'scale(0.75)', rActual.exhala, function() {
                    hacerFase('Pausa…', 'scale(0.75)', rActual.retiene2, function() {
                        if (!rRunning) return;
                        ciclo++;
                        document.getElementById('ciclo-actual').textContent = ciclo;
                        if (ciclo < rActual.ciclos) {
                            cicloSiguiente();
                        } else {
                            document.getElementById('resp-texto').textContent = '✅ Completado';
                            document.getElementById('resp-completado').style.display = 'block';
                            document.getElementById('btn-resp-detener').style.display = 'none';
                            rRunning = false;
                        }
                    });
                });
            });
        });
    }

    cicloSiguiente();
}
</script>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
