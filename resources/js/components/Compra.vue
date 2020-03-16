<template>
  <div>
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Mantenedor de Compras</a>
      </div>

      <div class="card shadow">
        <div class="card-header border-0">
            <div class="row">
              <div class="col">

              <button
                @click="abrirModal('compra','registrar')"
                type="button"
                class="btn btn-default float-left"
              >Nuevo</button>

            </div>
                      <div class="col">

              <!-- Calendario Inicio -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                  </div>
                  <input
                    class="form-control"
                    v-model="inicio"
                    placeholder="Select date"
                    type="date"
                  />
                </div>
              </div>

            </div>

            <div class="col">
              <!-- Calendario Fin -->
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="ni ni-calendar-grid-58"></i>
                    </span>
                  </div>
                  <input
                    class="form-control"
                    v-model="fin"
                    placeholder="Seleccione Fecha"
                    type="date"
                  />
                </div>
              </div>
            </div>
                        <div class="col">                 
              <button class="btn btn-icon btn-3 btn-success" type="button" @click.prevent="listarComprasFecha()">
                <span class="btn-inner--icon">
                  <i class="ni ni-button-play"></i>
                </span>

              </button>
            </div>
          </div>
          <div class="row">
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Compra</th>
                    <th scope="col">Monto</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="compra in arrayCompra" :key="compra.id">
                    <td>
                        <h5>{{ compra.date }}</h5>
                    </td>
                    <td>
                      <small class="text-uppercase">{{ compra.detalle }}</small>
                    </td>
                    <td>${{compra.monto | currency}}</td>
                    <td class="text-right">
                      <div class="dropdown">
                        <a
                          class="btn btn-sm btn-icon-only text-light"
                          href="#"
                          role="button"
                          data-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                          <a
                            class="dropdown-item"
                            @click.prevent="abrirModal('compra','actualizar',compra)"
                          >Modificar</a>
                          <a
                            class="dropdown-item"
                            @click.prevent="eliminarCompra(compra.id)"
                          >Eliminar</a>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer py-4">
              <nav aria-label="...">
                <ul class="pagination justify-content-end mb-0">
                  <li class="page-item" v-if="pagination.current_page > 1">
                    <a
                      class="page-link"
                      tabindex="-1"
                      @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)"
                    >
                      <i class="fas fa-angle-left"></i>
                      <span class="sr-only">Atrás</span>
                    </a>
                  </li>
                  <li
                    class="page-item active"
                    v-for="page in pagesNumber"
                    :key="page"
                    :class="[page == isActived ? 'active' : '']"
                  >
                    <a
                      class="page-link"
                      @click.prevent="cambiarPagina(page,buscar,criterio)"
                      v-text="page"
                    ></a>
                  </li>
                  <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a
                      class="page-link"
                      @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)"
                    >
                      <i class="fas fa-angle-right"></i>
                      <span class="sr-only">Siguiente</span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--Inicio del modal agregar/actualizar-->
    <div
      class="modal fade"
      tabindex="-1"
      :class="{'mostrar' : modal}"
      role="dialog"
      aria-labelledby="myModalLabel"
      style="display: none; overflow-y: scroll;"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-text="tituloModal"></h4>
            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <form action method="post" enctype="multipart/form-data" class="form-horizontal">
              
              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                <div class="col-md-9">
                  <input
                    type="date"
                    v-model="fecha"
                    class="form-control"
                    placeholder="01/01/2020"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Compra</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="detalle"
                    class="form-control"
                    placeholder="1 Cajón de Tomate"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Monto</label>
                <div class="col-md-9">
                  <input type="text" v-model="monto" class="form-control" placeholder="Ej : 1500" />
                </div>
              </div>

              <div v-show="errorCompra" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error"></div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
            <button
              type="button"
              v-if="tipoAccion==1"
              class="btn btn-primary"
              @click="registrarCompra()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarCompra()"
            >Actualizar</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->
  </div>
</template>

<script>
import Swal from "sweetalert2";
import VueCurrencyFilter from "vue-currency-filter";

Vue.use(VueCurrencyFilter);
export default {
  props: ["ruta"],
  data() {
    return {
      id: 0,
      inicio: null,
      fin: null,
      fecha: null,
      detalle: "",
      monto: "",
      empresa_id: null,
      arrayCompra: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorCompra: 0,
      errorMostrarMsjCompra: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      criterio: "nombre",
      buscar: ""
    };
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    //Calcula los elementos de la paginación
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }

      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }

      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },
  methods: {

    listarComprasFecha(){
      let me = this;
      me.total = 0;
      me.total_invertido = 0;

      axios.post("/comprasporfecha", {
          inicio: me.inicio,
          fin: me.fin
        })
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCompra = respuesta.compras.data;
          me.pagination = respuesta.pagination;


        })
        .catch(function(error) {
          console.log(error);
        });
    },
    listarCompra(page, buscar, criterio) {
      let me = this;
      var url =
        "/compra?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCompra = respuesta.compras.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    obtenerEmpresa() {
      let me = this;
      var url = "/empresadelusuario";
      axios
        .get(url)
        .then(function(response) {
          me.empresa_id = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    cambiarPagina(page, buscar, criterio) {
      let me = this;
      //Actualiza la página actual
      me.pagination.current_page = page;
      //Envia la petición para visualizar la data de esa página
      me.listarCompra(page, buscar, criterio);
    },

    registrarCompra() {
      if (this.validarCompra()) {
        return;
      }

      let me = this;

      axios
        .post("/compra/registrar", {
          compra: this.detalle,
          monto: this.monto,
          empresa_id: this.empresa_id,
          fecha: this.fecha
          
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCompra(1, "", "compra");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Tu compra fue almacenada !"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarCompra() {
      if (this.validarCompra()) {
        return;
      }

      let me = this;

      axios
        .put("/compra/actualizar", {
          compra: this.detalle,
          monto: this.monto,
          empresa_id: this.empresa_id,
          id: this.id,
          fecha: this.fecha
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarCompra(1, "", "compra");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Tu compra fue actualizado !"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    eliminarCompra(id) {
      let me = this;

      Swal.fire({
        title: "¿Está Seguro?",
        text: "Esta acción no es reversible",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Bórralo"
      }).then(result => {
        if (result.value) {
          axios
            .put("/compra/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarCompra(1, "", "compra");
              Swal.fire(
                "Borrado!",
                "El registro fue liminado del sistema.",
                "success"
              );
            })
            .catch(function(error) {
              console.log(error);
            });
        }
      });
    },
    validarCompra() {
      this.errorCompra = 0;
      this.errorMostrarMsjCompra = [];

      if (!this.detalle)
        this.errorMostrarMsjCompra.push(
          "La compra no puede estar vacía."
        );

      if (this.errorMostrarMsjCompra.length) this.errorCompra = 1;

      return this.errorCompra;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.detalle = "";
      this.monto = "";
      this.fecha = null;
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "compra": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Compra";
              this.detalle = "";
              this.monto = "";
              this.tipoAccion = 1;
              break;
            }

            case "actualizar": {
              
              this.modal = 1;
              this.tituloModal = "Actualizar compra";
              this.tipoAccion = 2;
              this.id = data["id"];
              this.detalle = data["detalle"];
              this.monto = data["monto"];
              this.empresa_id = data["empresa_id"];
              this.fecha = data["date"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.obtenerEmpresa();
    this.listarCompra(1, this.buscar, this.criterio);
  }
};
</script>
