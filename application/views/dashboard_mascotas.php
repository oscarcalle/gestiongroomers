<main class="page-content">
  <div class="container-fluid">
    <div class="header">
      <div class="d-flex align-items-center justify-content-between flex-nowrap">
        <!-- Título -->
        <div class="d-flex align-items-center">
          <h1 class="mb-0">Mascotas</h1>
        </div>
        <!-- Tabs y botones -->
        <div class="d-flex align-items-center">
          <i class="fas fa-paw me-2"></i>
          <select
            id="sedesSelect"
            class="selectpicker border rounded me-2"
            multiple
            aria-label="Sedes"
            data-live-search="true"
            title="Selecciona Sedes"
            data-select-all-text="Todos"
            data-deselect-all-text="Ninguno"
            data-actions-box="true"
          >
            <?php foreach ($sedes as $sede): ?>
              <option value="<?php echo $sede['TenantId']; ?>" selected>
                <?php echo $sede['nombre']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <button id="btnFilter" class="btn btn-primary me-2">
            <i class="fa fa-filter"></i>
          </button>
          <button id="btnExcel" class="btn btn-success">
            <i class="fa fa-file-excel"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Tarjetas -->
    <div class="row">
      <!-- Tarjetas 0, 1 y 2 se mantienen sin cambios -->
      <div class="col-md-4 mb-4 card-item">
        <div class="card shadow-sm">
          <div class="card-body">
            <div id="loader-0" class="text-center mb-3">
              <div class="spinner-border text-primary"></div>
            </div>
            <div id="content-0"></div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4 card-item">
        <div class="card shadow-sm">
          <div class="card-body">
            <div id="loader-1" class="text-center mb-3">
              <div class="spinner-border text-primary"></div>
            </div>
            <div id="content-1"></div>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4 card-item">
        <div class="card shadow-sm">
          <div class="card-body">
            <div id="loader-2" class="text-center mb-3">
              <div class="spinner-border text-primary"></div>
            </div>
            <div id="content-2"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tarjeta para mostrar la tabla de resultados -->
    <div class="row">
      <div class="col-md-12 mb-4 card-item">
        <div class="card shadow-sm">
          <div class="card-body">
            <div id="loader-3" class="text-center mb-3" style="display: none;">
              <div class="spinner-border text-primary"></div>
            </div>
            <div id="content-3"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  $(document).ready(function () {
    // Función para actualizar el título del selectpicker
    function updateSelectText(selectId, titleText) {
      const $select = $('#' + selectId);
      const totalOptions = $select.find("option").length;
      const selectedItems = $select.val();
      const text = (!selectedItems || selectedItems.length === 0)
        ? `Selecciona ${titleText}`
        : selectedItems.length === totalOptions
          ? `Todas las ${titleText}`
          : null;

      if (text) {
        $select
          .siblings(".dropdown-toggle")
          .attr("title", text)
          .find(".filter-option-inner-inner")
          .text(text);
      }
    }

    // Lista de selectpickers a inicializar (verificando si existen en el DOM)
    const selectIds = ["sedesSelect", "areasSelect", "turnosSelect", "diasSelect"];
    selectIds.forEach(function(id) {
      if ($('#' + id).length) {
        $('#' + id + ' option').prop("selected", true); // Seleccionar todas las opciones
        updateSelectText(id, id.replace("Select", "").toLowerCase());
      }
    });

    // Actualizar el texto al cambiar la selección
    $(".selectpicker").on("changed.bs.select", function () {
      const selectId = $(this).attr("id");
      updateSelectText(selectId, selectId.replace("Select", "").toLowerCase());
    });

    // Endpoints de las tarjetas (ejemplo de otros endpoints)
    const endpoints = [
      "https://jsonplaceholder.typicode.com/posts/1",
      "https://jsonplaceholder.typicode.com/posts/2",
      "./dashboard_plansalud/top_mascotas",
      "https://jsonplaceholder.typicode.com/posts/4"
    ];
    let fetchedData = [];

    // Función que carga el contenido de la tarjeta indicada
    function loadCard(index) {
      const loader = document.getElementById(`loader-${index}`);
      const content = document.getElementById(`content-${index}`);
      loader.style.display = "block";
      content.innerHTML = "";

      fetch(endpoints[index])
        .then(response => response.json())
        .then(data => {
          fetchedData[index] = data;
          loader.style.display = "none";
          content.innerHTML = `
            <h5 class="card-title">${data.title}</h5>
            <p class="card-text">${data.body}</p>
          `;
        })
        .catch(() => {
          loader.style.display = "none";
          content.innerHTML = `<p class="text-danger">Error al cargar</p>`;
        });
    }

    // Función que recarga todas las tarjetas
    function reloadAll() {
      fetchedData = [];
      endpoints.forEach((_, index) => {
        loadCard(index);
      });
    }

    reloadAll();

    // Función para obtener datos de la solicitud
    function getFetchData() {
      return {
        sedes: $('#sedesSelect').val() || []
      };
    }

    // Función para validar la selección de sedes
    function validarSeleccion(fetchData) {
      if (!fetchData.sedes.length) {
        Swal.fire('Error', 'Por favor selecciona al menos una sede.', 'error');
        return false;
      }
      return true;
    }

    // Evento click para el botón de filtro
    $("#btnFilter").on("click", function () {
      const fetchData = getFetchData();
      if (!validarSeleccion(fetchData)) return;

      // Mostrar loader en la tarjeta 3 mientras se procesa la petición
      $("#loader-3").show();
      $("#content-3").html("");

      // Realizar la llamada a "./dashboard_plansalud/top_mascotas"
      fetch("./dashboard_plansalud/top_mascotas", {
        method: "POST", // Cambia a GET si es necesario
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(fetchData)
      })
        .then(response => response.json())
        .then(data => {
          $("#loader-3").hide();
          
          // Verificar que data sea un arreglo
          if (!Array.isArray(data)) {
            $("#content-3").html(`<p class="text-danger">La respuesta no es válida</p>`);
            return;
          }

          // Construir la tabla con los datos recibidos
          let tableHTML = `
            <table class="table">
              <thead>
                <tr>
                  <th>Sede</th>
                  <th>Mascota</th>
                  <th>Ventas</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
          `;
          data.forEach(item => {
            tableHTML += `
              <tr>
                <td>${item.Sede}</td>
                <td>${item.Mascota}</td>
                <td>${item.Ventas}</td>
                <td>${item.Total}</td>
              </tr>
            `;
          });
          tableHTML += `
              </tbody>
            </table>
          `;

          // Actualizar la tarjeta con la tabla
          $("#content-3").html(tableHTML);
        })
        .catch(() => {
          $("#loader-3").hide();
          $("#content-3").html(`<p class="text-danger">Error al cargar la información</p>`);
        });
    });
  });
</script>
