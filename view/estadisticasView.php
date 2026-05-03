<?php include __DIR__ . '/../view/statics/cabeceraView.php'; ?>

<main>
    <h1>📊 Mis Estadísticas</h1>
    <p class="subtitulo">Tu progreso de meditación de un vistazo</p>

    <!-- Tarjetas resumen -->
    <div class="stats-grid">
        <div class="stat-card stat-azul">
            <div class="stat-valor"><?php echo $minutosHoy; ?></div>
            <div class="stat-etiqueta">Minutos hoy</div>
        </div>
        <div class="stat-card stat-arena">
            <div class="stat-valor"><?php echo $minutosSem; ?></div>
            <div class="stat-etiqueta">Esta semana</div>
        </div>
        <div class="stat-card stat-verde">
            <div class="stat-valor"><?php echo $minutosMes; ?></div>
            <div class="stat-etiqueta">Este mes</div>
        </div>
        <div class="stat-card stat-azul">
            <div class="stat-valor"><?php echo $minutosTotal; ?></div>
            <div class="stat-etiqueta">Total general</div>
        </div>
    </div>

    <!-- Racha y sesiones -->
    <div class="stats-grid stats-grid-2">
        <div class="stat-card stat-arena">
            <div class="stat-valor"><?php echo $racha; ?> 🔥</div>
            <div class="stat-etiqueta">Días de racha</div>
        </div>
        <div class="stat-card stat-verde">
            <div class="stat-valor"><?php echo $totalSesiones; ?></div>
            <div class="stat-etiqueta">Sesiones totales</div>
        </div>
    </div>

    <!-- Gráficas -->
    <div class="graficas-grid">
        <div class="grafica-bloque">
            <h3>Minutos - últimos 7 días</h3>
            <canvas id="chartSemanal"></canvas>
        </div>
        <div class="grafica-bloque">
            <h3>Distribución por tipo</h3>
            <canvas id="chartTipos"></canvas>
        </div>
    </div>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
// Datos de la semana (PHP -> JS)
var datosSemana = <?php echo json_encode($datosSemana); ?>;
var datosTipos  = <?php echo json_encode($datosTipos); ?>;

// Preparar etiquetas últimos 7 días
var labels7 = [], minutos7 = [];
var hoy = new Date();
for (var i = 6; i >= 0; i--) {
    var d = new Date(hoy);
    d.setDate(hoy.getDate() - i);
    var dStr = d.toISOString().split('T')[0];
    labels7.push(['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'][d.getDay()]);
    var encontrado = datosSemana.find(function(r) { return r.dia === dStr; });
    minutos7.push(encontrado ? parseInt(encontrado.minutos) : 0);
}

new Chart(document.getElementById('chartSemanal'), {
    type: 'bar',
    data: {
        labels: labels7,
        datasets: [{
            label: 'Minutos',
            data: minutos7,
            backgroundColor: 'rgba(91,143,168,0.7)',
            borderColor: 'rgba(91,143,168,1)',
            borderWidth: 2,
            borderRadius: 6
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Doughnut tipos
var tipoLabels = datosTipos.map(function(t) { return t.tipo; });
var tipoCounts = datosTipos.map(function(t) { return parseInt(t.total); });
new Chart(document.getElementById('chartTipos'), {
    type: 'doughnut',
    data: {
        labels: tipoLabels.length ? tipoLabels : ['Sin datos'],
        datasets: [{
            data: tipoCounts.length ? tipoCounts : [1],
            backgroundColor: ['rgba(91,143,168,0.8)','rgba(212,163,115,0.8)','rgba(168,197,188,0.8)'],
            borderWidth: 0
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

<?php include __DIR__ . '/../view/statics/pieView.php'; ?>
