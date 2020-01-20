
require('./bootstrap');

window.Vue = require('vue');



Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('ventas-component', require('./components/Venta.vue').default);
Vue.component('empresa-component', require('./components/Empresa.vue').default);
Vue.component('certifica-component', require('./components/Certifica.vue').default);
Vue.component('productos-component', require('./components/Producto.vue').default);



const app = new Vue({
    el: '#app',
    data :{
        menu : 0
    }
});
