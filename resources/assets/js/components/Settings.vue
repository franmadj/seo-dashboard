<template>
    <div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Settings</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">  
            <div class="col-sm-12">
                <div class="row">
                    <div v-if="pass.error" class="alert alert-danger">
                        {{pass.error}}
                    </div>
                    <div v-if="pass.success" class="alert alert-success">
                        {{ pass.success }}
                    </div>
                    <div class="col-lg-6">

                        <h2>Change password</h2>
                        <form method="POST" action="">
                            <div class="form-group" >
                                <label for="new-password" class="control-label">Current Password</label>
                                <input  type="password" class="form-control" v-model="pass.currentPassword">
                            </div>
                            <div class="form-group" >
                                <label for="new-password" class="control-label">New Password</label>
                                <input  type="password" class="form-control" v-model="pass.newPassword">
                            </div>
                            <div class="form-group">
                                <label for="new-password-confirm" class="control-label">Confirm New Password</label>
                                <input id="new-password-confirm" type="password" class="form-control" v-model="pass.newPassword_confirmation">

                            </div>
                            <button type="button" class="btn btn-primary" @click="changePassword">
                                Change Password
                            </button>
                        </form>

                        <div class="panel panel-default imap-account-content">
                            <div class="panel-heading">
                                <h2>Imap Accounts</h2>
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalImapAccount" @click="editing=false, imap.domain='', imap.host='', imap.password=''">Create</button>

                            </div>
                            <div class="panel-body">




                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Domain</th>
                                            <th>Host</th>
                                            <th>Smtp</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for='(item, index) in imaps'>
                                            <td>{{item.domain}}</td>
                                            <td>{{item.imap}}</td>
                                            <td>{{item.smtp}}</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalImapAccount" 
                                                        @click="editing=true, imap.domain=item.domain, imap.imap=item.imap, imap.smtp=item.smtp,imap.id=item.id,imap.password=item.password, imap.index=index"><i class="fa fa-edit"></i> Update</button>

                                                <button class="btn btn-danger btn-xs delete-imapAccount" @click="deleteImapAccount(item.id, index)"><i class="fa fa-trash-o"></i> Delete</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>		




                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <h2>DomDetailer API Setteings</h2>
                        <form role="form">
                            <div class="form-group has-success">
                                <label class="control-label" >End Point</label>
                                <input type="text" class="form-control" v-model="domdetailer.endPoint">
                            </div>
                            <div class="form-group has-warning">
                                <label class="control-label" >API Key</label>
                                <input type="text" class="form-control" v-model="domdetailer.apiKey">
                            </div>
                            <button type="button" class="btn btn-primary" @click="saveApiSettings(domdetailer)">
                                Save
                            </button>

                        </form>

                        <h2>Bing API Setteings</h2>
                        <form role="form">
                            <input type="hidden" class="form-control" v-model="bing.queryParam.Market">


                            <input type="hidden" class="form-control" v-model="bing.queryParam.Safesearch">
                            <input type="hidden" class="form-control" v-model="bing.queryParam.freshness">


                            <div class="form-group has-success">
                                <label class="control-label" >End Point</label>
                                <input type="text" class="form-control" v-model="bing.endPoint">
                            </div>
                            <div class="form-group has-warning">
                                <label class="control-label" >API Key</label>
                                <input type="text" class="form-control" v-model="bing.apiKey">
                            </div>

                            <button type="button" class="btn btn-primary" @click="saveApiSettings(bing)">
                                Save
                            </button>




                        </form>

                        <h2>Hunter API Setteings</h2>
                        <form role="form">
                            <div class="form-group has-success">
                                <label class="control-label" >End Point</label>
                                <input type="text" class="form-control" v-model="hunter.endPoint">
                            </div>
                            <div class="form-group has-warning">
                                <label class="control-label" >API Key</label>
                                <input type="text" class="form-control" v-model="hunter.apiKey">
                            </div>
                            <button type="button" class="btn btn-primary" @click="saveApiSettings(hunter)">
                                Save
                            </button>

                        </form>



                    </div>
                </div>
            </div>



        </div>
        <!-- /#page-wrapper -->

        <!-- Modal -->
        <div class="modal fade" id="modalImapAccount" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editing?'Edit':'Create'}} Imap Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="imap.error" class="alert alert-danger">
                            {{imap.error}}
                        </div>
                        <div v-if="imap.success" class="alert alert-success">
                            {{ imap.success }}
                        </div>
                        <form role="form">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Email</label>
                                <input type="text" class="form-control" v-model='imap.domain'>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="inputWarning">Imap</label>
                                <input type="text" class="form-control" v-model='imap.imap'>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="inputWarning">Smtp</label>
                                <input type="text" class="form-control" v-model='imap.smtp'>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="inputWarning">Password</label>
                                <input type="password" class="form-control" v-model='imap.password'>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click='editing?updateImapAccount():addImapAccount()'>Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
    
    .imap-account-content{
        margin-top: 15px;
    }
</style>

<script>
    export default {
        data() {
            return {
                pass: {
                    error: '',
                    success: '',
                    currentPassword: '',
                    newPassword: '',
                    newPassword_confirmation: ''
                },
                domdetailer: {
                    meta_key: 'domdetailer',
                    endPoint: '',
                    apiKey: '',
                },
                bing: {
                    meta_key: 'bing',
                    endPoint: '',
                    apiKey: '',
                    queryParam: {
                        Market: '',
                        Safesearch: 'Strict',
                        freshness: '',

                    }
                },
                hunter: {
                    meta_key: 'hunter',
                    endPoint: '',
                    apiKey: '',
                },
                imap: {
                    imap: '',
                    smtp: '',
                    domain: '',
                    password: '',
                    id: '',
                    index: 0,
                    error: '',
                    success: '',

                },
                imaps: [],
                editing: false


            }
        },
        created() {
            this.getSettings();
            this.getImapAccounts();
        },
        methods: {
            getImapAccounts() {
                axios.get("imap-accounts").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data);
                        this.imaps = response.data.data;


                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })


            },
            addImapAccount() {
                this.imap.error = '';
                this.imap.success = '';
                axios.post("imap-accounts", this.imap).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.imaps.unshift(response.data.data);
                        $('#modalCampaign').modal('hide');
                        this.imap.success = "Created successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.imap.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            updateImapAccount() {
                this.imap.error = '';
                this.imap.success = '';
                axios.patch("imap-accounts/" + this.imap.id, this.imap).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.imaps[this.imap.index].domain = response.data.data.domain;
                        this.imaps[this.imap.index].imap = response.data.data.imap;
                        this.imaps[this.imap.index].smtp = response.data.data.smtp;
                        this.imaps[this.imap.index].password = response.data.data.password;
                        $('#modalImapAccount').modal('hide');
                        this.imap.success = "Updated successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.imap.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            deleteImapAccount(id, index) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.error = '';
                    this.success = '';
                    axios.delete('imap-accounts/' + id).then(response => {
                        if (response.status === 200) {
                            this.imaps.splice(index, 1);
                            this.success = "Deleted successfully !";

                        }
                    })
                }
            },
            changePassword() {
                this.pass.error = '';
                this.pass.success = '';
                axios.post("change-password", this.pass).then(response => {
                    if (response.data.status == 200) {
                        this.pass.success = "Password changed successfully !";
                    } else {
                        this.pass.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            saveApiSettings(apiKey) {

                axios.post("settings", apiKey).then(response => {
                    if (response.data.status == 200) {
                        this.pass.success = "Updated successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.pass.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })

            },
            getSettings() {
                axios.get("settings").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data);
                        response.data.data.forEach(e => {
                            switch (e.metaKey) {
                                case 'domdetailer':
                                    this.domdetailer.endPoint = e.endPoint;
                                    this.domdetailer.apiKey = e.apiKey;

                                    break;
                                case 'bing':
                                    this.bing.endPoint = e.endPoint;
                                    this.bing.apiKey = e.apiKey;
                                    this.bing.queryParam = e.queryParam;
                                    if (!this.bing.queryParam.Market)
                                        this.bing.queryParam.Market = '';
                                    if (!this.bing.queryParam.Safesearch)
                                        this.bing.queryParam.Safesearch = '';
                                    if (!this.bing.queryParam.freshness)
                                        this.bing.queryParam.freshness = '';
                                    break;
                                case 'hunter':
                                    this.hunter.endPoint = e.endPoint;
                                    this.hunter.apiKey = e.apiKey;
                                    break;

                            }

                        })

                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })

            }
        }
    }
</script>
