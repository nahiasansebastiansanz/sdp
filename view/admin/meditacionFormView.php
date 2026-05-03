<?php include __DIR__ . '/../../view/statics/cabeceraView.php'; ?>

<main>
    <h1><?php echo $meditacion ? '✏️ Editar meditación' : '🧘 Nueva meditación'; ?></h1>

    <form id="medForm" method="POST" action="/<?php echo $projectName; ?>/controller/MeditacionController.php">
        <input type="hidden" name="action" value="<?php echo $meditacion ? 'actualizar' : 'guardar'; ?>">
        <?php if ($meditacion): ?>
            <input type="hidden" name="id_meditacion" value="<?php echo $meditacion->getIdMeditacion(); ?>">
        <?php endif; ?>

        <div class="formularios">
            <label for="titulo">Título: *</label>
            <input type="text" id="titulo" name="titulo" required
                   value="<?php echo htmlspecialchars($meditacion ? $meditacion->getTitulo() : ''); ?>">

            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" rows="3" style="resize:vertical;"
            ><?php echo htmlspecialchars($meditacion ? ($meditacion->getDescripcion() ?? '') : ''); ?></textarea>

            <label for="id_categoria">Categoría: *</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">Selecciona...</option>
                <?php foreach ($categorias as $item): ?>
                    <?php $cat = $item['modelo']; ?>
                    <option value="<?php echo $cat->getIdCategoria(); ?>"
                        <?php echo ($meditacion && $meditacion->getIdCategoria() == $cat->getIdCategoria()) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat->getIcono() . ' ' . $cat->getNombre()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="nivel">Nivel: *</label>
            <select id="nivel" name="nivel" required>
                <option value="">Selecciona...</option>
                <?php foreach (['principiante','intermedio','avanzado'] as $n): ?>
                    <option value="<?php echo $n; ?>" <?php echo ($meditacion && $meditacion->getNivel() == $n) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($n); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="duracion_min">Duración (min): *</label>
            <input type="number" id="duracion_min" name="duracion_min" min="1" required
                   value="<?php echo $meditacion ? $meditacion->getDuracionMin() : ''; ?>">

            <label for="icono">Icono (emoji):</label>
            <input type="text" id="icono" name="icono" maxlength="10" placeholder="🧘"
                   value="<?php echo htmlspecialchars($meditacion ? ($meditacion->getIcono() ?? '🧘') : '🧘'); ?>">

            <label for="instrucciones">Instrucciones / Guión:</label>
            <textarea id="instrucciones" name="instrucciones" rows="5" style="resize:vertical;"
            ><?php echo htmlspecialchars($meditacion ? ($meditacion->getInstrucciones() ?? '') : ''); ?></textarea>
        </div>
    </form>

    <div class="dashboardActions">
        <a href="/<?php echo $projectName; ?>/controller/MeditacionController.php?action=listarAdmin" class="botones">Cancelar</a>
        <button type="submit" form="medForm" class="botones botones-primario">
            <?php echo $meditacion ? '💾 Actualizar' : '➕ Crear meditación'; ?>
        </button>
    </div>
</main>

<?php include __DIR__ . '/../../view/statics/pieView.php'; ?>
