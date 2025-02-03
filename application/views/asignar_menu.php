<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>

<main class="page-content">
    <div class="container-fluid">
    <div class="form-group">
    <label for="idnivel">Seleccionar Nivel</label>
    <select class="form-control" id="idnivel" name="idnivel" required>
        <option value="">Seleccione un nivel</option>
        <?php foreach ($niveles as $nivel): ?>
            <option value="<?= $nivel['idnivel']; ?>"><?= $nivel['nombre']; ?></option>
        <?php endforeach; ?>
    </select>
</div>

<div id="menu_tree" class="mt-3"></div>

<button id="asignarMenuBtn" class="btn btn-primary mt-3">Asignar Menú</button>

<script>
    $(document).ready(function() {
    // Cargar los menús en formato de árbol
    $('#idnivel').on('change', function() {
        const idnivel = $(this).val();
        if (idnivel) {
            $.ajax({
                url: '<?= base_url("asignarmenu/obtener_menus_tree/") ?>' + idnivel,
                method: 'GET',
                success: function(data) {
                    const menuData = JSON.parse(data);
                    console.log(menuData);

                    // Destruye y vacía cualquier árbol existente antes de volver a cargar los datos
                    $('#menu_tree').jstree("destroy").empty();

                    // Inicializa el árbol sin cascada automática en la configuración inicial
                    $('#menu_tree').jstree({
                        'core': {
                            'data': menuData, // Datos de la API
                            'check_callback': true,
                            'themes': {
                                'stripes': true
                            }
                        },
                        'checkbox': {
                            'three_state': false,   // Desactiva el estado de cascada inicial
                            'cascade': ''           // Sin cascada en el inicio
                        },
                        'plugins': ["checkbox"]
                    });

                    // Cuando el árbol esté completamente cargado
                    $('#menu_tree').on('loaded.jstree', function () {
                        // Recorre cada nodo en el JSON y selecciona los nodos con `selected: true`
                        menuData.forEach(item => {
                            selectNodes(item);
                        });
                        
                        // Después de cargar el estado inicial, activa la cascada hacia abajo y hacia arriba
                        $('#menu_tree').jstree(true).settings.checkbox.cascade = 'down+up';
                        $('#menu_tree').jstree(true).redraw(true); // Redibuja el árbol para aplicar la cascada
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar el árbol de menús:", error);
                }
            });
        }
    });

    // Función recursiva para seleccionar nodos según el JSON
    function selectNodes(node) {
        if (node.state && node.state.selected) {
            $('#menu_tree').jstree(true).select_node(node.id); // Selecciona el nodo si está marcado
        }
        if (node.children) {
            node.children.forEach(child => selectNodes(child)); // Llama recursivamente para cada hijo
        }
    }

    $('#menu_tree').on('select_node.jstree', function (e, data) {
        // Si el nodo seleccionado tiene hijos, no selecciona automáticamente a los hijos
        if (data.node.children_d.length > 0) {
            // Solo selecciona los hijos si el nodo tiene hijos
            // Esta es la condición que previene la selección de hijos si es un nodo hoja
            data.instance.select_node(data.node.children_d);
        }

        // Selecciona el padre si se selecciona un hijo
        let parent = data.instance.get_parent(data.node);
        if (parent && !data.instance.is_selected(parent)) {
            data.instance.select_node(parent);
        }

        // Verifica si el nodo padre debe seguir seleccionándose
        while (parent) {
            const siblings = data.instance.get_node(parent).children;
            const allSiblingsSelected = siblings.every(id => data.instance.is_selected(id));
            
            if (allSiblingsSelected) {
                // Si todos los hermanos están seleccionados, selecciona el padre
                data.instance.select_node(parent);
                parent = data.instance.get_parent(parent);
            } else {
                parent = null;
            }
        }
    });

    $('#menu_tree').on('deselect_node.jstree', function (e, data) {
        // Si se deselecciona un nodo padre, deselecciona todos sus hijos
        if (data.node.children_d.length > 0) {
            data.instance.deselect_node(data.node.children_d); // Deselecciona los hijos
        }

        // Deselecciona el nodo padre si ningún hijo está seleccionado
        let parent = data.instance.get_parent(data.node);
        while (parent) {
            const siblings = data.instance.get_node(parent).children;
            const anySiblingSelected = siblings.some(id => data.instance.is_selected(id));
            
            // Si ningún hermano está seleccionado, deselecciona el padre
            if (!anySiblingSelected) {
                data.instance.deselect_node(parent);
            }
            
            parent = data.instance.get_parent(parent);
        }
    });

    $('#asignarMenuBtn').on('click', function() {
        let selectedMenus = $('#menu_tree').jstree("get_checked", true); // Obtener nodos completos
        const idnivel = $('#idnivel').val();

        // Crear un Set para almacenar los IDs únicos de los menús seleccionados y sus padres
        const menuIds = new Set();

        selectedMenus.forEach(node => {
            // Agregar el idmenu del nodo seleccionado
            menuIds.add(node.id.replace(/^j1_/, ''));

            // Agregar el idmenu del nodo padre, si no es el root (usualmente "#")
            if (node.parent !== "#") {
                menuIds.add(node.parent.replace(/^j1_/, ''));
            }
        });

        // Convertir el Set a un array para obtener el formato deseado
        const menuIdsArray = Array.from(menuIds);

        if (menuIdsArray.length > 0) {
            console.log(menuIdsArray);
            
            // Llamada AJAX para enviar el array de IDs al backend
            
             $.ajax({
                url: '<?= base_url("asignarmenu/asignar_menus") ?>',
               method: 'POST',
                data: { idnivel, menus: menuIdsArray },
                 success: function(response) {
                    const res = JSON.parse(response);
                    Swal.fire({
                        icon: res.success ? 'success' : 'error',
                        title: res.success ? 'Éxito' : 'Error',
                        text: res.message
                     });
                }
             });
            
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor, seleccione al menos un menú.'
            });
        }
    });
});

</script>

    </div>
</main>