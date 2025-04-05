<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<main class="page-content">
  <div class="container-fluid">

  <nav aria-label="breadcrumb" class="main-breadcrumb mb-1">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="./Inicio"><i class="fas fa-home"></i> Inicio</a></li>
        <li class="breadcrumb-item"><i class="fa fa-globe"></i> Indicadores</li>
        <li class="breadcrumb-item active" aria-current="page">Planes Vencidos</li>
      </ol>
    </nav>

    <h2>Planes Vencidos</h2>

    <!-- Filtros -->
    <div class="form-row mb-3">
      <div class="col-12 col-md-2 mb-2">
        <input type="date" id="fecha_inicio" class="form-control" placeholder="Fecha inicio">
      </div>
      <div class="col-12 col-md-2 mb-2">
        <input type="date" id="fecha_fin" class="form-control" placeholder="Fecha fin">
      </div>
      <div class="col-12 col-md-2 mb-2">
      <div class="input-group">
      <span class="input-group-text"><i class="fa fa-paw"></i></span>
        <select id="especie" class="form-control">
            <option value="">Todas las especies</option>
            <option value="Perro">Perro</option>
            <option value="Gato">Gato</option>
        </select>
        </div>
      </div>
      <div class="col-12 col-md-2 mb-2">
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-building"></i></span>
          <select id="sede" class="form-control">
            <!-- Las opciones se llenarán dinámicamente con JavaScript -->
          </select>
        </div>
      </div>
      <div class="col-12 col-md-2 mb-2">
        <button id="btnFiltrar" class="btn btn-primary w-100">
          <i class="fa fa-filter"></i> Filtrar
        </button>
      </div>
      <div class="col-12 col-md-2 mb-2">
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-cog"></i> Configuración
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
          <!-- <li><a class="dropdown-item" href="#"><i class="fa fa-file-excel icon-excel"></i> Descargar Reporte Excel</a></li> -->
          <li><a class="dropdown-item" href="#" id="btnMotivos"><i class="fa fa-file icon-file"></i> Motivos No Renovación</a></li>
        </ul>
      </div>

      </div>
    </div>
    
    <div class="mb-4">
      <span class="icon-circle bg-info text-white">
        <i class="fa fa-info"></i>
      </span>
      Información actualizada al <span id="f_fin"></span>
    </div>

    <div class="mb-4 alert alert-warning" role="alert" style="display: none;">
      * Los campos de contacto y renovación son editables. Se guardará automáticamente al seleccionar una opción.
    </div>

    <div class="form-group tabla-buscador" style="display: none;">
  <div class="input-group">
    <span class="input-group-text"><i class="fa fa-search"></i></span>
    <input type="text" id="buscador" class="form-control" placeholder="Buscar en la tabla...">
  </div>
</div>



    <div class="tabla-container" style="overflow-x: auto;display: none;">

    <div class="d-flex justify-content-end">
        <button onclick="exportarExcel()" class="btn btn-success mb-2">
            <i class="fas fa-file-excel"></i> Exportar a Excel
        </button>
    </div>

      <!-- Tabla de Resultados -->
      <table class="table table-bordered bgtable" style="font-size:12px" id="miTabla">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Mascota</th>
            <th>Especie</th>
            <th>Edad</th>
            <th>Fecha Fin</th>
            <th>Días Vencidos</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Sede</th>
            <th>Fue Contactado?</th>
            <th>Hizo Renovación?</th>
            <th>Responsable</th>
            <th>Motivo No Renovación</th>
          </tr>
        </thead>
        <tbody id="tablaResultados">
          <!-- Aquí se llenarán los datos de la tabla -->
        </tbody>
      </table>
    </div>

  </div>
</main>


<!-- Modal para Motivos No Renovación -->
<div class="modal fade" id="motivosModal" tabindex="-1" role="dialog" aria-labelledby="motivosModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="motivosModalLabel">Motivos No Renovación</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">



      <form id="motivoForm" class="d-flex align-items-center">
      <div class="form-group flex-grow-1 me-2">
            <input type="text" class="form-control" id="motivo" required placeholder="Motivo de no renovación" autocomplete="off">
            <input type="hidden" id="motivoId">
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary"  style="margin-left: 5px;"><i class="fa fa-save"></i> Guardar</button>
          </div>
        </form>

         <!-- Loader Spinner -->
         <div id="loader2">
              <i class="fa fa-paw spinner"></i>
          </div>
         
        <table class="table table-bordered mt-3 bgtable" id="motivosTable">
          <thead id="tableHeader" style="display: none;">
            <tr>
              <th>#</th>
              <th>Motivo</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody id="motivosTableBody">
            <!-- Aquí se llenarán los datos de la tabla -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<script>
  // Cargar opciones del select "sede" y responsables al cargar la página
  document.addEventListener('DOMContentLoaded', () => {
// Obtener la fecha de hace 7 días
const fechaHace7Dias = new Date();
fechaHace7Dias.setDate(fechaHace7Dias.getDate() - 7);
const fechaInicio = fechaHace7Dias.toISOString().split('T')[0];

// Obtener la fecha actual
const today = new Date().toISOString().split('T')[0];

// Establecer valores en los inputs y etiquetas
document.getElementById('fecha_inicio').value = fechaInicio;

document.getElementById('fecha_fin').value = today;
document.getElementById('f_fin').innerHTML  = today;

    // Cargar sedes
    cargarSedes();

    // Filtrar datos al hacer clic en el botón
    document.getElementById('btnFiltrar').addEventListener('click', cargarDatos);
  });

  // Función para cargar las sedes
  function cargarSedes() {
    fetch('./planesvencidos/obtener_sedes')
      .then(response => response.json())
      .then(data => {
        console.log(data);
        const sedeSelect = document.getElementById('sede');

        // Limpiar contenido previo
        sedeSelect.innerHTML = '';

        // Mostrar "Todas las sedes" solo si hay más de una opción
        if (data.length > 1) {
          sedeSelect.innerHTML = '<option value="">Todas las sedes</option>';
        }

        // Agregar las opciones obtenidas de la consulta
        data.forEach(sede => {
          const option = `<option value="${sede.sede}">${sede.sede}</option>`;
          sedeSelect.insertAdjacentHTML('beforeend', option);
        });
      })
      .catch(error => console.error('Error:', error));
  }

  // Función para cargar los datos desde el servidor y llenar la tabla
  function cargarDatos() {
    const fechaInicio = document.getElementById('fecha_inicio').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const sede = document.getElementById('sede').value;
    const especie = document.getElementById('especie').value;

    $('#loader').css('visibility', 'visible');

    // Llamada al controlador para obtener los planes
    fetch(`./planesvencidos/obtener_planes?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}&sede=${sede}&especie=${especie}`)
      .then(response => {
        if (!response.ok) {
          throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
      })
      .then(data => {
        cargarTabla(data); // Cargar los datos en la tabla
        $('#loader').css('visibility', 'hidden');
        $('.alert-warning').css('display', 'block');
        $('.tabla-container').css('display', 'block');
        $('.tabla-buscador').css('display', 'block');
        // Mostrar mensaje de éxito
        //Swal.fire('¡Éxito!', 'Datos cargados correctamente', 'success');
        toastr.success('Datos cargados correctamente');
      })
      .catch(error => {
        console.error('Error:', error);
        $('#loader').css('visibility', 'hidden');
        // Mostrar mensaje de error
        Swal.fire('¡Error!', 'Hubo un problema al obtener los datos', 'error');
      });
  }

  async function cargarResponsables() {
    try {
        const response = await fetch('./planesvencidos/obtener_responsables');
        const data = await response.json();
        console.log('Responsables:', data);

        const responsables = data;
        document.querySelectorAll('[data-field="responsable_contacto"]').forEach(select => {
            select.innerHTML = '';  // Eliminar todo el contenido antes de añadir las nuevas opciones

            // Agregar la opción vacía si el valor es nulo o vacío
            const responsableContacto = select.getAttribute('data-value');
            if (!responsableContacto) {
                select.innerHTML += '<option value=""></option>';
            }

            responsables.forEach(responsable => {
                const option = document.createElement("option");
                option.value = responsable.user || responsable.email;
                option.textContent = responsable.user || responsable.email;
                select.appendChild(option);
            });

            // Si hay un valor para el responsable, marcarlo como seleccionado
            if (responsableContacto) {
                const option = Array.from(select.options).find(opt => opt.value === responsableContacto);
                if (option) {
                    option.selected = true;
                }
            }
        });
    } catch (error) {
        console.error('Error al cargar los responsables:', error);
    }
}

async function cargarMotivos() {
    try {
        const response = await fetch('./planesvencidos/obtener_motivos');
        const data = await response.json();
        console.log('Motivos:', data);

        const motivos = data;
        document.querySelectorAll('[data-field="motivo"]').forEach(select => {
            select.innerHTML = '';  // Eliminar todo el contenido antes de añadir las nuevas opciones

            // Agregar la opción vacía si el valor es nulo o vacío
            const motivo = select.getAttribute('data-value');
            if (!motivo) {
                select.innerHTML += '<option value=""></option>';
            }

            motivos.forEach(motivoItem => {
                const option = document.createElement("option");
                option.value = motivoItem.id;
                option.textContent = motivoItem.motivo;
                select.appendChild(option);
            });

            // Si hay un valor para el motivo, marcarlo como seleccionado
            if (motivo) {
                const option = Array.from(select.options).find(opt => opt.value === motivo);
                if (option) {
                    option.selected = true;
                }
            }
        });
    } catch (error) {
        console.error('Error al cargar los motivos:', error);
    }
}

// Función para cargar la tabla con datos editables
async function cargarTabla(data) {
    const tabla = document.getElementById('tablaResultados');
    tabla.innerHTML = ''; // Limpiar la tabla previa

    data.forEach((plan, index) => {
        const edad = calcularEdad(plan.fecha_nacimiento); // Calcular la edad
        const row = `
        <tr>
            <td>${index + 1}</td>
            <td>${plan.cliente}</td>
            <td>${plan.mascota}</td>
            <td>${plan.especie}</td>
            <td>${edad}</td>
            <td>${plan.fecha_fin}</td>
            <td>${plan.dias_vencidos}</td>
            <td>${plan.movil}</td>
            <td>${plan.email}</td>
            <td>${plan.sede}</td>
            <td>
                <select data-id="${plan.plan_salud_id}" data-field="contactado" data-value="${plan.contactado || ''}" class="form-control">
                    <option value=""></option>
                    <option value="SI" ${plan.contactado === 'SI' ? 'selected' : ''}>SI</option>
                    <option value="NO" ${plan.contactado === 'NO' ? 'selected' : ''}>NO</option>
                </select>
            </td>
            <td>
                <select data-id="${plan.plan_salud_id}" data-field="renovo" data-value="${plan.renovo || ''}" class="form-control">
                    <option value=""></option>
                    <option value="SI" ${plan.renovo === 'SI' ? 'selected' : ''}>SI</option>
                    <option value="NO" ${plan.renovo === 'NO' ? 'selected' : ''}>NO</option>
                </select>
            </td>
            <td>
                <select data-id="${plan.plan_salud_id}" data-field="responsable_contacto" data-value="${plan.responsable_contacto || ''}" class="form-control">
                    <!-- Los responsables se cargarán aquí -->
                </select>
            </td>
            <td>
                <select data-id="${plan.plan_salud_id}" data-field="motivo" data-value="${plan.motivo || ''}" class="form-control">
                    <!-- Los motivos se cargarán aquí -->
                </select>
            </td>
        </tr>`;
        tabla.insertAdjacentHTML('beforeend', row);
    });

    // Esperar a que se carguen los responsables y motivos
    await cargarResponsables();
    await cargarMotivos();

    // Asignar los valores seleccionados a los campos de responsables y motivos
    data.forEach(plan => {
        const responsableSelect = document.querySelector(`[data-id="${plan.plan_salud_id}"][data-field="responsable_contacto"]`);
        const motivoSelect = document.querySelector(`[data-id="${plan.plan_salud_id}"][data-field="motivo"]`);

        // Seleccionar el responsable que ya tiene el plan
        if (responsableSelect && plan.responsable_contacto) {
            const option = Array.from(responsableSelect.options).find(opt => opt.value === plan.responsable_contacto);
            if (option) {
                option.selected = true;
            }
        }

        // Seleccionar el motivo que ya tiene el plan
        if (motivoSelect && plan.motivo) {
            const option = Array.from(motivoSelect.options).find(opt => opt.value === plan.motivo);
            if (option) {
                option.selected = true;
            }
        }
    });
}



  // Actualizar valores en base de datos cuando se edita en la tabla
  document.getElementById('tablaResultados').addEventListener('change', (event) => {
    const target = event.target;

    // Verifica si el elemento que desencadenó el evento es un <select>
    if (target.tagName === 'SELECT') {
      const planId = target.getAttribute('data-id');

      // Encuentra la fila correspondiente al <select> que se ha cambiado
      const row = target.closest('tr');

      // Recoge todos los valores de los selects en la fila
      const contactado = row.querySelector('select[data-field="contactado"]').value;
      const renovo = row.querySelector('select[data-field="renovo"]').value;
      const responsable_contacto = row.querySelector('select[data-field="responsable_contacto"]').value;
      const motivo = row.querySelector('select[data-field="motivo"]').value;

      // Crea un objeto con todos los datos
      const data = {
        plan_salud_id: planId,
        contactado: contactado,
        renovo: renovo,
        responsable_contacto: responsable_contacto,
        motivo: motivo
      };

      // Verifica que los datos están correctamente formados antes de enviar
      console.log('Datos a enviar:', data); // Agregado para depuración

      // Si "Renovo" es "NO" y no se ha seleccionado un motivo
      if (renovo === 'NO' && !motivo) {
        Swal.fire('Información', 'Debe seleccionar el motivo de no renovación.', 'info');
        //return; // Salir de la función para evitar el envío
      }

      // Envía los datos al controlador
      fetch('./planesvencidos/guardar_contacto', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          if (data.status === 'success') {
            //Swal.fire('¡Éxito!', 'Datos guardados correctamente', 'success');
            toastr.success('Datos guardados correctamente');
          } else {
            Swal.fire('¡Error!', 'Hubo un problema al guardar los datos', 'error');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire('¡Error!', 'Hubo un problema con la conexión', 'error');
        });
    }
  });

  // Filtro de búsqueda en la tabla
  document.getElementById('buscador').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tablaResultados tr');

    rows.forEach(row => {
      const cells = row.getElementsByTagName('td');
      const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
      row.style.display = rowText.includes(filter) ? '' : 'none';
    });
  });


  // Función para calcular la edad a partir de la fecha de nacimiento
  function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();

    const mes = hoy.getMonth() - nacimiento.getMonth();
    const dia = hoy.getDate() - nacimiento.getDate();

    // Ajusta la edad si aún no ha pasado el cumpleaños este año
    if (mes < 0 || (mes === 0 && dia < 0)) {
      edad--;
    }

    return edad === 1 ? "1 año" : `${edad} años`;
  }

document.getElementById('btnMotivos').addEventListener('click', () => {
  cargarMotivoss();
  $('#motivosModal').modal('show');
});

// Función para cargar los motivos desde el servidor
function cargarMotivoss() {
  $('#loader2').css('visibility', 'visible');

  fetch('./planesvencidos/obtener_motivos')
    .then(response => response.json())
    .then(data => {
      $('#loader2').css('display', 'none');
      // Comprobar si hay datos
      if (data.length > 0) {
        // Mostrar las cabeceras de la tabla
        document.getElementById('tableHeader').style.display = '';

      const motivosTableBody = document.getElementById('motivosTableBody');
      motivosTableBody.innerHTML = '';
      data.forEach((motivo, index) => {
        const row = `
          <tr>
            <td>${index + 1}</td>
            <td>${motivo.motivo}</td>
            <td>
              <button class="btn btn-warning btn-edit" data-id="${motivo.id}"><i class="fa fa-edit"></i></button>
              <button class="btn btn-danger btn-delete" data-id="${motivo.id}"><i class="fa fa-trash"></i></button>
            </td>
          </tr>`;
        motivosTableBody.insertAdjacentHTML('beforeend', row);
      });

      // Añadir eventos a los botones de editar y eliminar
      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', editMotivo);
      });
      document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', deleteMotivo);
      });
    } else {
        // Si no hay datos, ocultar las cabeceras
        document.getElementById('tableHeader').style.display = 'none';
      }
    })
    .catch(error => {
      // Ocultar el loader en caso de error
      $('#loader2').css('display', 'none');
      console.error('Error:', error);
    });
}

// Manejar la creación y actualización del motivo
document.getElementById('motivoForm').addEventListener('submit', (event) => {
  event.preventDefault();
  const motivo = document.getElementById('motivo').value;
  const motivoId = document.getElementById('motivoId').value;

  const url = motivoId ? './planesvencidos/actualizar_motivo' : './planesvencidos/agregar_motivo';
  const data = { motivo, id: motivoId };

  fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }
      return response.json();
    })
    .then(data => {
      toastr.success('Motivo guardado correctamente');
      cargarMotivoss();
      document.getElementById('motivoForm').reset();
      //$('#motivosModal').modal('hide');
    })
    .catch(error => {
      console.error('Error:', error);
      toastr.error('Error al guardar el motivo');
    });
});

// Función para editar un motivo
function editMotivo(event) {
  const id = event.currentTarget.getAttribute('data-id');

  fetch(`./planesvencidos/obtener_motivo/${id}`)
    .then(response => response.json())
    .then(data => {
      document.getElementById('motivo').value = data.motivo;
      document.getElementById('motivoId').value = data.id;
      $('#motivosModal').modal('show');
    })
    .catch(error => console.error('Error:', error));
}

// Función para eliminar un motivo
function deleteMotivo(event) {
    const motivoId = event.currentTarget.getAttribute('data-id');

    Swal.fire({
        title: '¿Desea eliminar este motivo?',
        text: 'Este proceso no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, proceder a eliminar el motivo
            fetch(`./planesvencidos/eliminar_motivo?id=${motivoId}`, {
                method: 'DELETE',
            })
            .then(response => {
                if (response.ok) {
                    // Actualizar la tabla después de eliminar
                    cargarMotivoss();
                    Swal.fire(
                        '¡Eliminado!',
                        'El motivo ha sido eliminado correctamente.',
                        'success'
                    );
                } else {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el motivo.',
                        'error'
                    );
                }
            })
            .catch(error => {
                Swal.fire(
                    'Error',
                    'Hubo un problema en la conexión.',
                    'error'
                );
            });
        }
    });
}


function exportarExcel() {
            // Obtener la tabla HTML
            let tabla = document.getElementById('miTabla');

            // Índices de las columnas a omitir
            const columnasOmitir = [10, 11, 12, 13]; //OMITIR COLUMNAS

            // Crear una nueva estructura para las filas
            let datos = [];
            let filas = tabla.querySelectorAll('tr');

            filas.forEach((fila) => {
                let celdas = fila.querySelectorAll('th, td');
                let nuevaFila = [];

                celdas.forEach((celda, celdaIndex) => {
                    // Solo incluir columnas que NO estén en columnasOmitir
                    if (!columnasOmitir.includes(celdaIndex)) {
                        nuevaFila.push(celda.innerText);
                    }
                });

                datos.push(nuevaFila);
            });

            // Convertir el arreglo filtrado en una hoja de Excel
            let hoja = XLSX.utils.aoa_to_sheet(datos);

            // Crear un libro de trabajo y añadir la hoja
            let libro = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(libro, hoja, 'Datos');

            // Exportar el archivo
            XLSX.writeFile(libro, 'reporte_planes_vencidos.xlsx');
        }
</script>