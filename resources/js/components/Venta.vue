<template>
  <div>

    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block">Sistema de Ventas</a>
      </div>

      <div class="row">
        <div class="col-xl-6 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <button class="btn btn-icon btn-3 btn-secondary" type="button">
                    <span class="btn-inner--icon">
                      <i class="ni ni-box-2"></i>
                    </span>
                    <span class="btn-inner--text">Productos</span>
                  </button>
                </div>
                <div class="col">
                  <input
                    v-on:change="listarProducto(1, buscar)"
                    type="text"
                    v-model="buscar"
                    class="form-control float-right"
                    placeholder="Buscar ..."
                    style="max-width:300px;"
                  />
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table align-items-center table-flush">
                  <thead>
                    <tr>
                      <th scope="col">Producto</th>
                      <th scope="col">Valor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      style="cursor:pointer;"
                      v-for="producto in arrayProducto"
                      :key="producto.id"
                      @click.prevent="
                                                seleccionarProducto(producto)
                                            "
                    >
                      <th scope="row">
                        <div class="media align-items-center">
                          <a class="img-thumbnail mr-3">
                            <img
                              v-bind:src="
                                                                producto.imagen
                                                            "
                              height="35"
                              width="35"
                            />
                          </a>
                          <div class="media-body">
                            <span
                              class="mb-0 text-sm"
                              v-text="
                                                                producto.nombre
                                                            "
                            ></span>
                          </div>
                        </div>
                      </th>
                      <td>
                        ${{
                        producto.precio | currency
                        }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-6">
          <div class="card shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <button
                    @click.prevent="borrarVenta()"
                    class="btn btn-icon btn-3 btn-secondary"
                    type="button"
                  >
                    <span class="btn-inner--icon">
                      <i class="ni ni-curved-next"></i>
                    </span>
                    <span class="btn-inner--text">Venta</span>
                  </button>
                </div>
                <div class="col">
                  <button
                    @click.prevent="tomarPedido()"
                    class="btn btn-icon btn-3 btn-default float-right"
                    type="button"
                  >
                    <span class="btn-inner--icon">
                      <i class="ni ni-bag-17"></i>
                    </span>
                    <span class="btn-inner--text">Tomar Pedido</span>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table align-items-center table-flush">
                  <thead>
                    <tr>
                      <th scope="col">Producto</th>
                      <th scope="col">Cant</th>
                      <th scope="col">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="producto in arraySeleccion" :key="producto.id">
                      <th scope="row">
                        <div class="media align-items-center">
                          <div class="media-body">
                            <span
                              class="mb-0 text-sm"
                              v-text="
                                                                producto.nombre
                                                            "
                            ></span>
                          </div>
                        </div>
                      </th>
                      <td>
                        <input
                          v-on:change="
                                                        actualizarPrecio(
                                                            producto
                                                        )
                                                    "
                          type="number"
                          v-model="producto.cantidad"
                          class="form-control"
                          style="width:60px; border: 0px solid;"
                        />
                      </td>
                      <td>${{ producto.total | currency }}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td>Total :</td>
                      <td>
                        <b>${{ sumatotal | currency }}</b>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import VueCurrencyFilter from "vue-currency-filter";

Vue.use(VueCurrencyFilter);
Vue.use("../js/Impresora.js");
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
      usuario_id: null,
      boleta: 1,
      arrayProducto: [],
      arraySeleccion: [],
      sumatotal: 0,
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
    borrarVenta() {
      this.arraySeleccion = [];
      this.sumatotal = 0;
    },

    calcularVenta() {
      this.sumatotal = 0;
      this.arraySeleccion.forEach(p => {
        this.sumatotal = this.sumatotal + p.total;
      });
    },

    isProductoEnArray(array, producto) {
      let esta = false;
      array.forEach(p => {
        if (p.id == producto.id) {
          esta = true;
        }
      });
      return esta;
    },

    actualizarPrecio(producto) {
      let me = this;
      let posicion = me.arraySeleccion.indexOf(producto);

      if (producto.cantidad < 1) {
        me.arraySeleccion.splice(posicion, 1);
        this.sumatotal = this.sumatotal - producto.precio;
      }

      me.arraySeleccion.forEach(p => {
        if (p.id == producto.id) {
          me.arraySeleccion[posicion].total =
            producto.cantidad * producto.precio;
        }

        me.arraySeleccion.sort();
        this.calcularVenta();
      });
    },

    seleccionarProducto(producto) {
      let me = this;
      producto.cantidad = 1;
      producto.total = producto.precio;

      if (me.arraySeleccion.length == 0) {
        me.arraySeleccion.push(producto);
        me.sumatotal = producto.precio;
      } else if (!this.isProductoEnArray(me.arraySeleccion, producto)) {
        me.arraySeleccion.push(producto);
        me.sumatotal = me.sumatotal + producto.precio;
      } else {
        Swal.fire("Ups..", "Ya tienes este producto agregado", "error");
      }

      me.arraySeleccion.sort();
    },

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

    obtenerUsuario() {
      let me = this;
      var url = "/obtenerusuario";
      axios
        .get(url)
        .then(function(response) {
          me.usuario_id = response.data;
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

    imprimirElemento(elemento, total){
      var ventana = window.open('', 'PRINT', 'height=400,width=400');
      ventana.document.write('<html><head><title>Ticket de Venta</title></head><body>');
      ventana.document.write('####################');
      ventana.document.write('<h1>TICKET DE VENTA</h1>');
      ventana.document.write('####################');
      ventana.document.write('<br><table>');
      ventana.document.write(elemento);
      ventana.document.write('<br>');
      ventana.document.write('####################');
      ventana.document.write('<br><b>Total : ' + total +'</b>');
      ventana.document.write('</table></body></html>');
      ventana.document.write('<br>');
      ventana.document.write('######### FIN ###########');
      ventana.document.write('<br>');
      ventana.document.write('<br>');
      ventana.document.write('<br>');
      ventana.document.write('<br>');
      ventana.document.write('<hr>');
      ventana.document.close();
      ventana.focus();
      ventana.print();
      ventana.close();
      
    },

    tomarPedido() {
      
      let me = this;

      if (this.arraySeleccion.length == 0) {
        return;
      }
      var productos_html = "";
      var total_productos = 0;
      var total_cantidad = 0;
      axios
        .post("/venta/registrar", {
          boleta: me.boleta,
          empresa_id: me.empresa_id,
          monto: me.sumatotal,
          usuario_id: me.usuario_id,
          arraySeleccion: me.arraySeleccion
        })
        .then(function(response) {
          if(response.data)
          {

            me.arraySeleccion.forEach(producto => {
              total_productos = total_productos + parseInt(producto.precio);
              total_cantidad =  total_cantidad + parseInt(producto.cantidad);
              productos_html = productos_html  + "<hr>" + producto.cantidad + "  "+ producto.nombre +"  $ "+ producto.precio  +"";
            });

          if(me.imprimirElemento(productos_html, total_productos))
          {
            
          
          }
         
         
         // var doc = window.open('../ticket.html', "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=400, height=400");
          
          //doc.write = "<tr><td>Cant</td><td>Productos</td><td>Precios</td></tr>" + productos_html;
          //doc.window.print();
          //doc.close();
          me.borrarVenta();
          }else{
            
            Swal.fire({
            icon: "error",
            title: "Oops ...",
            text: "Ocurrió un error al guardar el pedido !"
          });
          }
        })
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
    this.obtenerUsuario();
    this.listarProducto(1, this.buscar, this.criterio);
  }
};
</script>
