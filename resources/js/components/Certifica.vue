<template>

    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8 cuerpo">
        <div class="container-fluid">
            <div class="header-body">
                <main class="main">
                    <div class="container-fluid">
                        <div class="card">
                    
                            <div class="card-header">
                                <img src="img/sii_icon.png" width="24" class="">
                            </div>

                            <div class="card-body">
                            
                                    <div class="alert alert-warning" role="alert" v-if="!isFirma()">
                                        <span class="alert-inner--icon"><i class="ni ni-bell-55"></i></span>
                                        <span class="alert-inner--text"><strong>Nota :</strong> Usted ya cuenta con una firma electrónica vigente.</span>
                                    </div>

                                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                <input type="file" id="firma" class="form-control-file" accept="*/p12" @change="onFileChangeFirma"/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                <input type="password" class="form-control" v-model="clave_firma" placeholder="Confirmar Contraseña"/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                <button class="btn btn-icon btn-3 btn-primary" type="button" @click="registrarFirma()">
                                                    <span class="btn-inner--icon"><i class="ni ni-cloud-upload-96"></i></span>                              
                                                    <span class="btn-inner--text text-bold">Subir Firma</span>      
                                                </button>
                                            </div>
                                        </div>

                                    </form>   
                            </div>    
                        </div>
                    </div>
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
                firma : null,
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
                firmaok : '',
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
            onFileChangeFirma(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createFirma(files[0]);
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
            createFirma(file) {
      
                this.extension = file.name.split(".")[1];

                if(this.extension == 'p12'){                 
                    this.firma = file;
                }else{
                  this.firma = null;
                  document.getElementById("firma").value = null;
                  alert('La extensión del archivo debe ser .P12');
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

            registrarFirma(){


               if (this.validarFirma()){
                    return;
                } 

                const formData = new FormData();
                formData.append('firma', this.firma);
                formData.append('clave', this.clave_firma);               
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }
            
                axios.post('/empresa/registrarFirma',formData,config).then(function (response) {
                    //me.cerrarModal();
                    //me.listarEmpresa(1,'','rut');
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

                if (!this.clave_firma) this.errorMostrarMsjEmpresa.push("La contraeña no puede estar vacía.");

                if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

                return this.errorEmpresa;
            },

            validarFirma(){
                this.errorEmpresa=0;
                this.errorMostrarMsjEmpresa =[];

                if (this.clave_firma == "") this.errorMostrarMsjEmpresa.push("La contraeña no puede estar vacía.");
                if (this.firma == null) this.errorMostrarMsjEmpresa.push("La firma debe ser cargada.");
                
                if (this.errorMostrarMsjEmpresa.length) this.errorEmpresa = 1;

                return this.errorEmpresa;
            },

            isFirma(){
            axios.post('/empresa/revisarFirma').then(function(response){

                return response.data;

            });
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
            
            this.isFirma();

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