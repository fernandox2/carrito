<template>
  <div>
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Mantenedor de Productos</a>
      </div>

      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <button
                @click="abrirModal('producto','registrar')"
                type="button"
                class="btn btn-default float-left"
              >Nuevo</button>

              <input
                v-on:change="listarProducto(1,buscar)"
                type="text"
                v-model="buscar"
                class="form-control float-right"
                placeholder="Buscar ..."
                style="max-width:300px;"
              />
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Producto</th>
                    <th scope="col">descripcion</th>
                    <th scope="col">Valor</th>
                    <th scope="col">stock</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="producto in arrayProducto" :key="producto.id">
                    <th scope="row">
                      <div class="media align-items-center">
                        <a class="img-thumbnail mr-3">
                          <img v-bind:src="producto.imagen" height="35" width="35" />
                        </a>
                        <div class="media-body">
                          <span class="mb-0 text-sm" v-text="producto.nombre"></span>
                        </div>
                      </div>
                    </th>
                    <td>
                      <textarea
                        v-text="producto.descripcion"
                        style="border:0px;"
                        class="form-control"
                        rows="3"
                        disabled
                      ></textarea>
                    </td>
                    <td>${{producto.precio | currency}}</td>
                    <td>
                      <span v-text="producto.stock"></span>
                    </td>
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
                            @click.prevent="abrirModal('producto','actualizar',producto)"
                          >Modificar</a>
                          <a
                            class="dropdown-item"
                            @click.prevent="eliminarProducto(producto.id)"
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
                <label class="col-md-3 form-control-label" for="text-input">Producto</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="nombre"
                    class="form-control"
                    placeholder="Ej : Completo Italiano Mediano"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                <div class="col-md-9">
                  <input
                    type="text"
                    v-model="descripcion"
                    class="form-control"
                    placeholder="Ej : Pan de 22cm - Palta Hass - Mayonesa Casera"
                  />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">precio</label>
                <div class="col-md-9">
                  <input type="text" v-model="precio" class="form-control" placeholder="Ej : 1500" />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">stock</label>
                <div class="col-md-9">
                  <input type="text" v-model="stock" class="form-control" placeholder="Ej : 100" />
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 form-control-label" for="text-input">Imagen</label>
                <div class="col-md-9">
                  <img :src="image" height="48" width="48" />
                  <input type="file" id="imagen" accept="image/*" @change="onFileChange" />
                  <!-- <input type="text" v-model="imagen_corporativa" class="form-control" placeholder="Formato JPG o PNG"> -->
                </div>
              </div>

              <div v-show="errorProducto" class="form-group row div-error">
                <div class="text-center text-error">
                  <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error"></div>
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
              @click="registrarProducto()"
            >Guardar</button>
            <button
              type="button"
              v-if="tipoAccion==2"
              class="btn btn-primary"
              @click="actualizarProducto()"
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
      nombre: "",
      image: null,
      extension: "",
      descripcion: "",
      precio: "",
      stock: 0,
      empresa_id: null,
      arrayProducto: [],
      modal: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorProducto: 0,
      errorMostrarMsjProducto: [],
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
    onFileChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) return;
      this.createImage(files[0]);
    },
    createImage(file) {
      if (file.size < 512000) {
        this.extension = file.name.split(".")[1];
        let reader = new FileReader();
        reader.onload = e => {
          this.image = e.target.result;
        };
        reader.readAsDataURL(file);
      } else {
        this.image = null;
        alert(
          "El tamaño del logo no debe ser mayor a 512 KB y debe ser en formato JPG"
        );
      }
    },
    listarProducto(page, buscar, criterio) {
      let me = this;
      var url =
        "/producto?page=" +
        page +
        "&buscar=" +
        buscar +
        "&criterio=" +
        criterio;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayProducto = respuesta.productos.data;
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
      me.listarProducto(page, buscar, criterio);
    },
    registrarProducto() {
      if (this.validarProducto()) {
        return;
      }

      let me = this;

      axios
        .post("/producto/registrar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          stock: this.stock,
          precio: this.precio,
          image: this.image,
          empresa_id: this.empresa_id,
          extension: this.extension
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarProducto(1, "", "nombre");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Tu producto fue almacenado !"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    actualizarProducto() {
      if (this.validarProducto()) {
        return;
      }

      let me = this;

      axios
        .put("/producto/actualizar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          stock: this.stock,
          precio: this.precio,
          image: this.image,
          empresa_id: this.empresa_id,
          extension: this.extension,
          id: this.id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarProducto(1, "", "nombre");
          Swal.fire({
            icon: "success",
            title: "Excelente ...",
            text: "Tu producto fue actualizado !"
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    eliminarProducto(id) {
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
            .put("/producto/eliminar", {
              id: id
            })
            .then(function(response) {
              me.listarProducto(1, "", "nombre");
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
    validarProducto() {
      this.errorProducto = 0;
      this.errorMostrarMsjProducto = [];

      if (!this.nombre)
        this.errorMostrarMsjProducto.push(
          "El nombre del producto no puede estar vacío."
        );

      if (this.errorMostrarMsjProducto.length) this.errorProducto = 1;

      return this.errorProducto;
    },
    cerrarModal() {
      this.modal = 0;
      this.tituloModal = "";
      this.nombre = "";
      this.descripcion = "";
      this.precio = "";
      this.image = "";
      this.stock = "";
    },
    abrirModal(modelo, accion, data = []) {
      switch (modelo) {
        case "producto": {
          switch (accion) {
            case "registrar": {
              this.modal = 1;
              this.tituloModal = "Registrar Producto";
              this.nombre = "";
              this.descripcion = "";
              this.tipoAccion = 1;
              break;
            }

            case "actualizar": {
              this.modal = 1;
              this.tituloModal = "Actualizar producto";
              this.tipoAccion = 2;
              this.id = data["id"];
              this.nombre = data["nombre"];
              this.descripcion = data["descripcion"];
              this.stock = data["stock"];
              this.precio = data["precio"];
              this.image = data["imagen"];
              this.empresa_id = data["empresa_id"];
              break;
            }
          }
        }
      }
    }
  },
  mounted() {
    this.obtenerEmpresa();
    this.listarProducto(1, this.buscar, this.criterio);
  }
};
</script>
