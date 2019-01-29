<template>



    <div>
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Messaging</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Templates</li>
                    </ol>
                </nav>
                <h1 class="page-header">Templates</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">  
            <div class="col-sm-12">
                <div v-if="error" class="alert alert-danger">
                    {{error}}
                </div>
                <div v-if="success" class="alert alert-success">
                    {{ success }}
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalTemplates" @click="editing=false, template.text='', template.name=''">Create</button>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Template</th>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for='(item, index) in templates'>
                                    <td>{{item.name}}</td>
                                  
                                    <td>
                                        <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalTemplates" 
                                                @click="editing=true, template.text=item.text, template.name=item.name, template.subject=item.subject, template.id=item.id, template.index=index"><i class="fa fa-edit"></i> Update</button>
                            
                            <button class="btn btn-danger btn-xs delete-template" @click="deleteTemplate(item.id, index)"><i class="fa fa-trash-o"></i> Delete</button>
                            </td>
                            </tr>
                            </tbody>
                        </table>		
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-wrapper -->
        <!-- Modal -->
        <div class="modal fade" id="modalTemplates" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editing?'Edit':'Create'}} Template</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Name</label>
                                <input type="text" class="form-control" v-model='template.name'>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Subject</label>
                                <input type="text" class="form-control" v-model='template.subject'>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputWarning">Template Text</label>
                                
                                <textarea rows="15" class="form-control" v-model='template.text'></textarea>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click='editing?updateTemplate():addTemplate()'>Save changes</button>
                    </div>
                </div>
            </div>
        </div>


    </div>



</template>

<script>

    export default {
        name: "templates",
        data() {
            return {
                error: false,
                success: false,
                templates: [],
                template: {
                    name: '',
                    text: '',
                    subject: '',
                    id: '',
                    index: 0
                },
                editing: false


            }
        },
        created() {
            this.getTemplates();
        },
        methods: {
            getTemplates() {
                axios.get("templates").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data);
                        this.templates = response.data.data;
                    } 

                }).catch(error => {
                    console.log(error);
                })


            },
            addTemplate() {
                this.error = '';
                this.success = '';
                axios.post("templates", this.template).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.templates.unshift(response.data.data);
                        $('#modalTemplates').modal('hide');
                        this.success = "Created successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            updateTemplate() {
                this.error = '';
                this.success = '';
                axios.patch("templates/" + this.template.id, this.template).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.templates[this.template.index].name = response.data.data.name;
                        this.templates[this.template.index].url = response.data.data.url;
                        $('#modalTemplates').modal('hide');
                        this.success = "Updated successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            deleteTemplate(id, index) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.error = '';
                    this.success = '';
                    axios.delete('templates/' + id).then(response => {
                        if (response.status === 200) {
                            this.templates.splice(index, 1);
                            this.success = "Deleted successfully !";

                        }
                    })
                }
            },

        }
    }
</script>
