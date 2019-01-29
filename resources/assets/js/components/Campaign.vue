<template>

    <div>
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Messaging</a></li>
                        <li class="breadcrumb-item"><a href="/campaigns">Campaigns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{campaignName}}</li>
                    </ol>
                </nav>
                <h1 class="page-header">{{campaignName}}</h1>

            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">  


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div v-if="error" class="alert alert-danger">
                            {{error}}
                        </div>
                        <div v-if="success" class="alert alert-success">
                            {{ success }}
                        </div>
                        <div class="panel-heading">
                            Campaign
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#keywords" data-toggle="tab">Keywords</a>
                                </li>
                                <li><a href="#ex-domains" data-toggle="tab">Excluded Domains</a>
                                </li>
                                <li><a href="#settings" data-toggle="tab">Settings</a>
                                </li>
                                <li><router-link style="margin-left: 10px;" :to="`/search-results/${$route.params.id}/${campaignName}`" tag="button" class="btn btn-secondary">Search results</router-link>
                                </li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="keywords">
                                    <h3>Search Keywords</h3>
                                    <button style="margin-bottom: 15px;" class="btn btn-success" data-toggle="modal" data-target="#modalCampaign" @click="editing=false, keyword.word=''">Add Keyword</button>

                                    <button style="margin-bottom: 15px;" class="btn btn-approve" @click="showOpenDialog('keywordFile')">Import Keywords</button>

                                    <span style="margin-left:5px;" v-if="!keywordLoaded">Loading..</span>
                                    <input @change="attachFile($event,'keywords')" style="display: none" type="file" id="keywordFile">

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Keyword</th>

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for='(item, index) in keywords'>
                                                <td>{{item.word}}</td>
                                                <td>
                                                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalCampaign" 
                                                            @click="editing=true, keyword.word=item.word, keyword.id=item.id, keyword.index=index"><i class="fa fa-edit"></i> Update</button>

                                                    <button class="btn btn-danger btn-xs delete-campaign" @click="deleteKeyword(item.id, index)"><i class="fa fa-trash-o"></i> Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="ex-domains">
                                    <h3>Excludeded Domains</h3>
                                    <button style="margin-bottom: 15px;" class="btn btn-success" data-toggle="modal" data-target="#modalExcludedDom" @click="editing=false, keyword.domain=''">Exclude domain</button>

                                    <button style="margin-bottom: 15px;" class="btn btn-approve" @click="showOpenDialog('exFile')">Import Excluded Domains</button>
                                    <input @change="attachFile($event,'excluded-domain')" style="display: none" type="file" id="exFile">
                                    <span style="margin-left:5px;" v-if="!exLoaded">Loading..</span>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Domain</th>

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for='(item, index) in exDomains'>
                                                <td>{{item.domain}}</td>
                                                <td>
                                                    <button class="btn btn-info btn-xs" data-toggle="modal" data-target="#modalExcludedDom" 
                                                            @click="editing=true, exDomain.domain=item.domain, exDomain.id=item.id, exDomain.index=index"><i class="fa fa-edit"></i> Update</button>

                                                    <button class="btn btn-danger btn-xs delete-exdom" @click="deleteExDomain(item.id, index)"><i class="fa fa-trash-o"></i> Delete</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="settings">
                                    <h3>Settings</h3>
                                    <div class="row" style="margin: 20px 0;">  

                                        <div class="col-sm-3">
                                            <input type="hidden" v-model="bing.endPoint">
                                            <select class="form-control" v-model="bing.queryParam.Market" > <option value="">Select Market</option> <option value="ar-sa">ar-sa (Arabic)</option> <option value="da-dk">da-dk (Danish)</option> <option value="de-at">de-at (German-Austria)</option> <option value="de-ch">de-ch (German-Switzerland)</option> <option value="de-de">de-de (German-Germany)</option> <option value="en-au">en-au (English-Australia)</option> <option value="en-ca">en-ca (English-Canada)</option> <option value="en-gb" selected="">en-gb (English-UK)</option> <option value="en-id">en-id (English-Indonesia)</option> <option value="en-ie">en-ie (English-Ireland)</option> <option value="en-in">en-in (English-India)</option> <option value="en-my">en-my (English-Malaysia)</option> <option value="en-mx">en-mx (English-Mexico)</option> <option value="en-nz">en-nz (English-New Zealand)</option> <option value="en-ph">en-ph (English-Philippines)</option> <option value="en-us">en-us (English-United States)</option> <option value="en-za">en-za (English-South Africa)</option> <option value="es-ar">es-ar (Spanish-Argentina)</option> <option value="es-cl">es-cl (Spanish-Chile)</option> <option value="es-mx">es-mx (Spanish-Mexico)</option> <option value="es-es">es-es (Spanish-Spain)</option> <option value="es-us">es-us (Spanish-United States)</option> <option value="fi-fi">fi-fi (Finnish)</option> <option value="fr-be">fr-be (French-Belgium)</option> <option value="fr-ca">fr-ca (French-Canada)</option> <option value="fr-ch">fr-ch (French-Switzerland)</option> <option value="fr-fr">fr-fr (French-France)</option> <option value="it-it">it-it (Italian)</option> <option value="ja-jp">ja-jp (Japanese)</option> <option value="ko-kr">ko-kr (Korean)</option> <option value="nl-be">nl-be (Dutch-Belgium)</option> <option value="nl-nl">nl-nl (Dutch-Netherlands)</option> <option value="no-no">no-no (Norwegian)</option> <option value="pl-pl">pl-pl (Polish)</option> <option value="pt-pt">pt-pt (Portuguese-Portugal)</option> <option value="pt-br">pt-br (Portuguese-Brazil)</option> <option value="ru-ru">ru-ru (Russian)</option> <option value="sv-se">sv-se (Swedish)</option> <option value="tr-tr">tr-tr (Turkish)</option> <option value="zh-cn">zh-cn (Chinese)</option> <option value="zh-hk">zh-hk (Traditional Chinese-Hong Kong SAR)</option> <option value="zh-tw">zh-tw (Traditional Chinese-Taiwan)</option> </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="hidden" v-model="bing.apiKey">
                                            <select v-model="bing.queryParam.Safesearch" class="form-control" > <option value="">Select SafeSearch</option> <option value="Strict">Strict</option> <option  value="Moderate">Moderate</option> <option  value="Off">Off</option> </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <select v-model="bing.queryParam.freshness" class="form-control" > <option  value="">Select Freshness</option> <option value="Day">Day</option> <option value="Week">Week</option> <option value="Month">Month</option> </select>
                                        </div>

                                        <div class="col-sm-1">
                                            <button type="button" class="btn btn-primary" @click="saveApiSettings(bing)"> Save </button>
                                        </div>
                                    </div>





                                </div>

                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>

            </div>







        </div>
        <!-- /#page-wrapper -->



        <div class="modal fade" id="modalCampaign" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editing?'Edit':'Create'}} Keyword</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Keyword</label>
                                <input type="text" class="form-control" v-model='keyword.word'>
                            </div>


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click='editing?updateKeyword():addKeyword()'>Save changes</button>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="modalExcludedDom" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{editing?'Edit':'Create'}} Excluded Domain</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <label class="control-label" for="inputSuccess">Domain</label>
                                <input type="text" class="form-control" v-model='exDomain.domain'>
                            </div>


                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click='editing?updateExDomain():addExDomain()'>Save changes</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

</template>



<script>

    export default {
        name: 'campaign',

        data() {
            return {
                campaignName: '',
                error: false,
                success: false,
                keywords: [],
                keyword: {
                    word: '',
                    campaign_id: this.$route.params.id,
                    id: '',
                    index: 0
                },
                exDomains: [],
                exDomain: {
                    domain: '',
                    campaign_id: this.$route.params.id,
                    id: '',
                    index: 0
                },
                editing: false,

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
                keywordLoaded: true,
                exLoaded: true

            }
        },
        mounted() {
            this.getCampaign();

            this.getSettings();
        },
        methods: {
            getCampaign() {
                axios.get("campaigns/" + this.$route.params.id).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.campaignName = response.data.data.name;
                        console.log(response.data);
                        this.keywords = response.data.data.keywords;
                        this.exDomains = response.data.data.excluded_domains;


                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })


            },
            attachFile(event, name = 'excluded-domain') {
                this.error=false;
                this.success=false;

       
                let input = event.target;
                let vim = this;
                if (input.files && input.files[0]) {
                    this.keywordLoaded = false;
                    this.exLoaded = false;
                    
                    let reader = new FileReader();
                    let vm = this
                    reader.onload = function (e) {



                        axios.post(name + '/import', {file: e.target.result, campaign_id: vim.$route.params.id}).then(response => {
                            console.log(response);
                            vm.keywordLoaded = true;
                            vm.exLoaded = true;
                            if (response.data.status == 200) {
                                vim.success = "Added successfully !";
                                vim.getCampaign();
                                
                            } else {
                                vm.error = 'invalid File';
                            }

                        }).catch(error => {
                            vm.error = 'invalid File';
                            console.log(error);
                            vm.keywordLoaded = true;
                            vm.exLoaded = true;

                        })
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            },
            showOpenDialog(id) {
                document.getElementById(id).click()
            },
            addKeyword() {
                this.error = '';
                this.success = '';
                axios.post("keywords", this.keyword).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.keywords.unshift(response.data.data);
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
            updateKeyword() {
                this.error = '';
                this.success = '';
                axios.patch("keywords/" + this.keyword.id, this.keyword).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.keywords[this.keyword.index].word = response.data.data.word;
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
            deleteKeyword(id, index) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.error = '';
                    this.success = '';
                    axios.delete('keywords/' + id).then(response => {
                        if (response.status === 200) {
                            this.keywords.splice(index, 1);
                            this.success = "Deleted successfully !";

                        }
                    })
                }
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


                            }

                        })

                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })

            },

            addExDomain() {
                this.error = '';
                this.success = '';
                axios.post("excluded-domain", this.exDomain).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.exDomains.unshift(response.data.data);
                        $('#modalExcludedDom').modal('hide');
                        this.success = "Created successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            updateExDomain() {
                this.error = '';
                this.success = '';
                axios.patch("excluded-domain/" + this.exDomain.id, this.exDomain).then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        this.exDomains[this.exDomain.index].domain = response.data.data.domain;
                        $('#modalExcludedDom').modal('hide');
                        this.success = "Updated successfully !";
                        window.scroll({top: 0, left: 0, behavior: 'smooth'});
                    } else {
                        this.error = response.data.error.code
                    }

                }).catch(error => {
                    console.log(error);
                })
            },
            deleteExDomain(id, index) {
                if (confirm('Are you sure you want to delete this item?')) {
                    this.error = '';
                    this.success = '';
                    axios.delete('excluded-domain/' + id).then(response => {
                        if (response.status === 200) {
                            this.exDomains.splice(index, 1);
                            this.success = "Deleted successfully !";

                        }
                    })
                }
            },

        }
    }
</script>
