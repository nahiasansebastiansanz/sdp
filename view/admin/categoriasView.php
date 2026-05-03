<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1>🏷️ Gestión de Categorías</h1>

    <div class="panel-sesion">
        <h2>Nueva categoría</h2>
        <form id="catForm" method="POST" action="/<?php echo $projectName; ?>/controller/CategoriaController.php">
            <input type="hidden" name="action" id="cat-action" value="guardar">
            <input type="hidden" name="id_categoria" id="cat-id" value="">
            <div class="formularios">
                <label for="cat-nombre">Nombre: *</label>
                <input type="text" id="cat-nombre" name="nombre" required>

                <label for="cat-icono">Icono (emoji):</label>
                <input type="text" id="cat-icono" name="icono" maxlength="10" placeholder="🌿">

                <label for="cat-desc">Descripción:</label>
                <input type="text" id="cat-desc" name="descripcion">
            </div>
        </form>
        <div class="dashboardActions">
            <button type="button" class="botones" onclick="limpiarCatForm()">Cancelar</button>
            <button type="submit" form="catForm" class="botones botones-primario" id="cat-btn-submit">➕ Crear categoría</button>
        </div>
    </div>

    <!-- Tabla de categorías -->
    <?php if (empty($categorias)): ?>
        <p class="texto-vacio">No hay categorías aún.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead>
                <tr><th>Icono</th><th>Nombre</th><th>Descripción</th><th>Meditaciones</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $item):
                    $cat = $item['modelo'];
                    $num = $item['num_meditaciones'];
                ?>
                <tr>
                    <td style="font-size:1.5rem;"><?php echo htmlspecialchars($cat->getIcono()); ?></td>
                    <td><?php echo htmlspecialchars($cat->getNombre()); ?></td>
                    <td><?php echo htmlspecialchars($cat->getDescripcion() ?? ''); ?></td>
                    <td><?php echo $num; ?></td>
                    <td>
                        <button type="button" class="botones btn-sm"
                                onclick="editarCat(<?php echo $cat->getIdCategoria(); ?>, '<?php echo addslashes($cat->getNombre()); ?>', '<?php echo addslashes($cat->getIcono()); ?>', '<?php echo addslashes($cat->getDescripcion() ?? ''); ?>')">
                            ✏️ Editar
                        </button>
                        <form method="POST" action="/<?php echo $projectName; ?>/controller/CategoriaController.php" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar la categoría <?php echo addslashes($cat->getNombre()); ?>?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_categoria" value="<?php echo $cat->getIdCategoria(); ?>">
                            <button type="submit" class="botones botones-peligro btn-sm">🗑</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="dashboardActions">
        <button class="botones" onclick="history.back()">← Volver</button>
    </div>
</main>

<script>
function editarCat(id, nombre, icono, desc) {
    document.getElementById('cat-action').value     = 'actualizar';
    document.getElementById('cat-id').value         = id;
    document.getElementById('cat-nombre').value     = nombre;
    document.getElementById('cat-icono').value      = icono;
    document.getElementById('cat-desc').value       = desc;
    document.getElementById('cat-btn-submit').textContent = '💾 Actualizar';
    window.scrollTo(0, 0);
}
function limpiarCatForm() {
    document.getElementById('cat-action').value     = 'guardar';
    document.getElementById('cat-id').value         = '';
    document.getElementById('cat-nombre').value     = '';
    document.getElementById('cat-icono').value      = '';
    document.getElementById('cat-desc').value       = '';
    document.getElementById('cat-btn-submit').textContent = '➕ Crear categoría';
}
</script>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
