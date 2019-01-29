<template>



    <div>
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Messaging</a></li>

                        <li class="breadcrumb-item active" aria-current="page">Campaings</li>
                    </ol>
                </nav>
                <h1 class="page-header">Campaings</h1>
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
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalCampaign" @click="editing=false, campaign.name='', campaign.url=''">Create</button>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Url</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for='(item, index) in campaigns'>
                                    <td>{{item.name}}</td>
                                    <td>{{item.url}}</td>
                                    <td>
                                        <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalCampaign" 
                                                @click="editing=true, campaign.name=item.name, campaign.url=item.url, campaign.id=item.id, campaign.index=index"><i class="fa fa-edit"></i> Update</button>
                            <router-link :to="`/campaign/${item.id}`" tag="button" class="btn btn-primary btn-xs"><i class="fa fa-search"></i>View</router-link>
                            <button class="btn btn-danger btn-xs delete-campaign" @click="deleteCampaign(item.id, index)"><i class="fa fa-trash-o"></i> Delete</button>
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
        <div class="modal fade" id="modalCampaign" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editing?'Edit':'Create'}} Campaign</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Name</label>
                                <input type="text" class="form-control" v-model='campaign.name'>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputWarning">Url</label>
                                <input type="text" class="form-control" v-model='campaign.url'>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click='editing?updateCampaign():addCampaign()'>Save changes</button>
                    </div>
                </div>
            </div>
        </div>


    </div>



</template>

<script>

    export default {
        name: "campaigns",
        data() {
            return {
                error: false,
                success: false,
                campaigns: [],
                campaign: {
                    url: '',
                    name: '',
                    id: '',
                    index: 0
                },
                editing: false


            }
        },
        created() {
            this.getCampaigns();
        },
        methods: {
            getCampaigns() {
                axios.get("campaigns").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data);
                        this.campaigns = response.data.data;


                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })


            },
            addCampaign() {
                this.error = '';
                this.success = '';
                axios.post("campaigns", this.campaign).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.campaigns.unshift(response.data.data);
                        $('#modalCampaign').modal('hide');
                        this.success = "Created successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            updateCampaign() {
                this.error = '';
                this.success = '';
                axios.patch("campaigns/" + this.campaign.id, this.campaign).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.campaigns[this.campaign.index].name = response.data.data.name;
                        this.campaigns[this.campaign.index].url = response.data.data.url;
                        $('#modalCampaign').modal('hide');
                        this.success = "Updated successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            deleteCampaign(id, index) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.error = '';
                    this.success = '';
                    axios.delete('campaigns/' + id).then(response => {
                        if (response.status === 200) {
                            this.campaigns.splice(index, 1);
                            this.success = "Deleted successfully !";

                        }
                    })
                }
            },

        }
    }
</script>
