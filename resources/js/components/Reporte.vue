<template>
  <div>
    <div class="header bg-gradient-warning pb-8 pt-5 pt-md-2"></div>
    <div class="container-fluid mt--7">
      <div class="header-body">
        <a
          class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block"
        >Informe de Ventas</a>
      </div>


    <div class="card shadow">
      
      <div class="card-header border-0">
        <div class="row">
          <div class="col">
              <div class="alert alert-default alert-dismissible fade show" role="alert">
                <span class="alert-icon"><i class="ni ni-chart-bar-32"></i></span>
                <span class="alert-text text-uppercase"><strong>Informe de Ventas</strong></span>
              </div>
          </div>
        </div>
        <div class="row">
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
              <button class="btn btn-icon btn-3 btn-success" type="button" @click.prevent="listarVentas()">
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
                    <th scope="col">Día</th>
                    <th scope="col">Ventas</th>
                    <th scope="col">Recaudado ($)</th>
                    <th scope="col">Invertido ($)</th>
                    <th scope="col">Ganacias ($)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="venta in arrayVenta" :key="venta.id">
                    <td>
                        <h5>{{ venta.date}}</h5> 
                    </td>
                    <td>
                        {{ venta.numero_ventas}} 
                    </td>
                    <td>${{venta.monto_recaudado | currency}}</td>
                    <td>${{venta.monto_invertido | currency}}</td>
                    <td>${{venta.monto_recaudado - venta.monto_invertido | currency}}</td>
                  </tr>
                  <tr>
                    <td><h5 class="text-red">TOTALES</h5></td>
                    <td><h5 class="text-red">{{ total_ventas | currency}}</h5></td>
                    <td><h5 class="text-red">${{ total_recaudado | currency}}</h5></td>
                    <td><h5 class="text-red">${{ total_invertido | currency}}</h5></td>
                    <td><h5 class="text-red">${{ total | currency}}</h5></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
// Importaciones
import Swal from "sweetalert2";
import VueCurrencyFilter from "vue-currency-filter";
// Usos
Vue.use(VueCurrencyFilter);

export default {
  props: ["ruta"],
  data() {
    return {
      inicio:null,
      fin: null,
      empresa_id: null,
      total: 0,
      total_recaudado : 0,
      total_invertido : 0,
      total_ventas: 0,
      arrayVenta: []
    };
  },
  computed: {
    isActived: function() {},
    },
  methods: {  
    listarVentas() {
      let me = this;
      me.total = 0;
      me.total_ventas = 0;
      me.total_recaudado = 0;
      axios.post("/ventasporfecha", {
          inicio: this.inicio,
          fin: this.fin
        })
        .then(function(response) {
          var res = response.data;
          res.forEach(e => {
            if (e.monto_invertido == null ){
              e.monto_invertido = 0;
            }
            var ganancia = parseInt(e.monto_recaudado) - parseInt(e.monto_invertido);
            me.total = me.total + ganancia ;
            me.total_recaudado = me.total_recaudado + parseInt(e.monto_recaudado);
            me.total_invertido = me.total_invertido + parseInt(e.monto_invertido);
            me.total_ventas = me.total_ventas + e.numero_ventas;
          });
          me.arrayVenta = res;
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
  },
  mounted() {
    this.obtenerEmpresa();
  }
};
</script>
