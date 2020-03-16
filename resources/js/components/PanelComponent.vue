<template>
       <div>

           <div class="header bg-gradient-warning pb-8 pt-5 pt-md-5">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Ventas del Día</h5>
                                    <span class="h2 font-weight-bold mb-0"><b>{{ ventas_al_dia }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> Actualizado </span>
                                <span class="text-nowrap">hace 1 seg.</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Producto del Mes</h5>
                                    <span class="h2 font-weight-bold mb-0"><b>{{ producto_del_mes }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fab fa-product-hunt"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">Presente en
                                <span class="text-success mr-2"> <b> {{ cantidad_vendida }}</b> </span>
                                <span class="text-nowrap"> ventas</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Recaudación del Día</h5>
                                    <span class="h2 font-weight-bold mb-0"> <b>${{ recaudacion_al_minuto | currency }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> pesos</span>
                                <span class="text-nowrap">hasta el momento</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats mb-4 mb-xl-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Vendedor del Mes</h5>
                                    <span class="h2 font-weight-bold mb-0"><b> {{ vendedor_del_mes }}</b></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-muted text-sm">
                                <span class="text-success mr-2"> {{ ventas_del_vendedor }}</span>
                                <span class="text-nowrap"> ventas este mes</span>
                            </p>
                        </div>
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
export default {
  props: ["ruta"],
  data() {
    return {
      ventas_al_dia: 0,
      producto_del_mes: "",
      cantidad_vendida: 0,
      recaudacion_al_minuto: null,
      vendedor_del_mes: "",
      ventas_del_vendedor: 0
    };
  },
  computed: {

  },
  methods: {

    obtenerVentasDelDia() {
      let me = this;
      var url = "/ventasdeldia";
      axios
        .get(url)
        .then(function(response) {
          me.ventas_al_dia = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    obtenerProductoDelMes() {
      let me = this;
      var url = "/productodelmes";
      axios
        .get(url)
        .then(function(response) {
            
          me.producto_del_mes = response.data[0]['nombre'];
          me.cantidad_vendida = response.data[0]['cantidad'] ;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    recaudaciondeldia() {
      let me = this;
      var url = "/recaudaciondeldia";
      axios
        .get(url)
        .then(function(response) {
            me.recaudacion_al_minuto = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    vendedordelmes() {
      let me = this;
      var url = "/vendedordelmes";
      axios
        .get(url)
        .then(function(response) {
            me.vendedor_del_mes = response.data[0]['vendedor'];
            me.ventas_del_vendedor = response.data[0]['ventas_realizadas'];
        })
        .catch(function(error) {
          console.log(error);
        });
    },



  },
   created: function(){
      this.obtenerVentasDelDia();
      this.obtenerProductoDelMes();
      this.recaudaciondeldia();
      this.vendedordelmes();
    },
  mounted() {
      

      
  }
};
</script>

