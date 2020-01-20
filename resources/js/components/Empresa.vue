<template>
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8 cuerpo">
    <div class="container-fluid">
        <div class="header-body">
            
            <main class="main">

            <div class="container-fluid">
                <!-- Tabla de Listado -->
                <div class="card">
                    
                    <div class="card-header">

                       <button class="btn btn-outline-success" type="button" @click="abrirModal('empresa','registrar')">
                            <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                        </button>

                        <input v-on:change="listarEmpresa(1,buscar)" type="text" v-model="buscar" class="form-control float-right" placeholder="Buscar ..." style="max-width:300px;">

                     </div>

                      

                <div class="card-body">

                    <table class="table table-responsive" style="margin: 0px auto; max-width:100% !important;">
                    
                    <thead>
                        <tr class="text-primary text-uppercase">
                            <th class="text-center">RUT</th>
                            <th class="text-center">RAZON SOCIAL</th>
                            <th class="text-center">CORREO</th>
                            <th class="text-center">REPRESENTANTE LEGAL</th>
                            <th class="text-center">BOLETA ELECTRONICA</th>
                            <th class="text-center">LICENCIA</th>
                            <th class="text-center">#</th>
                        </tr>
                    
                    </thead>
                        <tbody>
                            <tr v-for="empresa in arrayEmpresa" :key="empresa.id">

                                <td class="text-center text-uppercase"><small v-text="empresa.rut"></small></td>

                                <td class="text-center text-uppercase" style="max-width: 400px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;"><small v-text="empresa.razon_social"></small></td>
                                
                                <td class="text-center text-uppercase"><small v-text="empresa.correo"></small></td>

                                <td class="text-center text-uppercase"><small v-text="empresa.representante_legal"></small></td>

                                <td v-if="empresa.ambiente_certificacion" class="text-center text-uppercase"><i class="ni ni-check-bold" style="color:green;"></i></td>
                                <td v-else class="text-center text-uppercase"><i class="ni ni-fat-remove" style="color:red;"></i></td>

                                <td v-if="empresa.estado_licencia" class="text-center text-uppercase"><i class="ni ni-check-bold" style="color:green;"></i></td>
                                <td v-else class="text-center text-uppercase"><i class="ni ni-fat-remove" style="color:red;"></i></td>

                                <td class="text-center">
                                   
                                   <i data-toggle="tooltip" title="Editar" class="ni ni-settings cursor-pointer" style="color: #5e72e4;" @click="abrirModal('empresa','actualizar',empresa)"></i>

                                    &nbsp;

                                    <i data-toggle="tooltip" title="Editar" class="ni ni-fat-delete cursor-pointer" style="color: red;" @click="eliminarEmpresa(empresa.id)"></i>
                                
                                </td>
                            </tr>                                
                        </tbody>
                </table>
<br>
                        <nav>
                            <ul class="pagination justify-content-center"> 
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Atras</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">>></a>
                                </li>
                            </ul>
                        </nav>

                </div>    

                </div>

            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RUT</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="rut" class="form-control" placeholder="Formato : xxxxxxxx-x">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Razón Social</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="razon_social" class="form-control" placeholder="Ingrese el nombre de la empresa">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Rubro</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="rubro" class="form-control" placeholder="Escoja un rubro">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Representante Legal</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="representante_legal" class="form-control" placeholder="Nombre del representante legal">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo Electrónico</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="correo" class="form-control" placeholder="Ingrese un correo electrónico válido">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Ciudad</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="ciudad" class="form-control" placeholder="Ingrese ciudad donde tributa la empresa">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Imagen Coorporativa</label>
                                    <div class="col-md-9">
                                        <img :src="image" />
                                        <input type="file" id="imagen" accept="image/*" @change="onFileChange"/>
                                       <!-- <input type="text" v-model="imagen_corporativa" class="form-control" placeholder="Formato JPG o PNG"> -->
                                    </div>
                                </div>

                                <div v-show="errorEmpresa" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEmpresa" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarEmpresa()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarEmpresa()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            <!-- Inicio del modal Eliminar -->
            <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Eliminar Empresa</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Estas seguro de eliminar la categoría?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger">Eliminar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- Fin del modal Eliminar -->
        </main>

</div>
    </div>
        </div>
</template>

<script>
    export default {
        props : ['ruta'],
        data (){
            return {
                empresa_id: 0,
                rut : '',
                image : null,
                extension : '',
                razon_social : '',
                rubro : '',
                representante_legal : '',
                correo : '',
                imagen_corporativa : '',
                estado_licencia : false,
                ambiente_certificacion : false,
                ruta_firma_electronica : '',
                clave_firma : '',
                ciudad : '',
                arrayEmpresa : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEmpresa : 0,
                errorMostrarMsjEmpresa : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        methods : {

            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                if(file.size < 512000){
                  this.extension = file.name.split(".")[1];
                  let reader = new FileReader();
                  reader.onload = (e) => {
                  this.image = e.target.result;
                };
                  reader.readAsDataURL(file);
                }else{
                  this.image = null;
                alert('El tamaño del logo no debe ser mayor a 512 KB y debe ser en formato JPG');
                }

            },
            listarEmpresa (page,buscar,criterio){
                let me=this;
                var url='/empresa?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEmpresa = respuesta.empresas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEmpresa(page,buscar,criterio);
            },
            registrarEmpresa(){
                if (this.validarEmpresa()){
                    return;
                }
                
                let me = this;

                axios.post('/empresa/registrar',{
                    'rut': this.rut,
                    'razon_social': this.razon_social,
                    'rubro': this.rubro,
                    'representante_legal': this.representante_legal,
                    'correo': this.correo,
                    'image': this.image,
                    'ciudad': this.ciudad,
                    'extension': this.extension
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpresa(1,'','rut');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarEmpresa(){
                if (this.validarEmpresa()){
                    return;
                }
                
                let me = this;

                axios.put('/empresa/actualizar',{
                    'rut': this.rut,
                    'razon_social': this.razon_social,
                    'rubro' : this.rubro,
                    'correo' : this.correo,
                    'representante_legal' : this.representante_legal,
                    'image' : this.image,
                    'ciudad' : this.ciudad,
                    'extension': this.extension,
                    'id': this.empresa_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEmpresa(1,'','rut');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            eliminarEmpresa(id){

                let me = this;
                var mensaje = confirm("¿Confirma que desea eliminar la empresa?");
                if (mensaje) 
                {
                    axios.put('/empresa/eliminar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEmpresa(1,'','rut');
                    }).catch(function (error) {
                        console.log(error);
                    });
                }else{
                    // Esto no hace nada :O
                }
                    
            },
            validarEmpresa(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa =[];

                if (!this.rut) this.errorMostrarMsjEmpresa.push("El rut de la empresa no puede estar vacío.");

                if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

                return this.errorEmpresa;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.rut='';
                this.razon_social='';
                this.rubro='';
                this.image='';
                this.correo='';
                this.ciudad='';
                this.estado_licencia=false;
                this.ambiente_certificacion=false;
                this.representante_legal='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "empresa":
                    {
                        switch(accion){

                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Empresa';
                                this.nombre= '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }

                            case 'actualizar':
                            {
                                this.modal=1;
                                this.tituloModal='Actualizar empresa';
                                this.tipoAccion=2;
                                this.empresa_id=data['id'];
                                this.rut = data['rut'];
                                this.razon_social= data['razon_social'];
                                this.rubro = data['rubro'];
                                this.representante_legal = data['representante_legal'];
                                this.correo = data['correo'];
                                this.ciudad = data['ciudad'];
                                this.image = data['imagen_corporativa'];
                                break;
                            }

                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEmpresa(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;

    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }

    img{
        max-height: 150px;
        margin:0px auto;
    }

    .cursor-pointer{
        cursor: pointer;
    }

    .cuerpo{
        min-height: 840px !important;
    }

    @media (max-width: 600px) {
    .cuerpo {
            min-height: 1000px !important;
        }
    }

</style>

