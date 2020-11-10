<template>

    <div>
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Messaging</a></li>
                        <li class="breadcrumb-item"><a href="/campaigns">Campaigns</a></li>
                        <li class="breadcrumb-item"><a :href="'/campaign/'+$route.params.id">{{$route.params.name}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Opportunities</li>
                    </ol>
                </nav>
                <h1 class="page-header">Opportunites for {{$route.params.name}}</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">  


            <div class="col-sm-12">
                <div v-if="error.length" class="alert alert-danger">

                    <h3>No contacts found</h3>
                    <p>No contact results for the following domains:</p>
                    <ul>
                        <li v-for="domaine in error">
                            {{domaine}}

                        </li>
                    </ul>
                </div>




                <div v-if="success.length" class="alert alert-success">
                    <h3>Contacts added</h3>
                    <p>The following domains have their contact added:</p>
                    <ul>
                        <li v-for="domain in success">
                            {{domain}}

                        </li>
                    </ul>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button type="button" class="btn btn-primary" @click="$router.push({ path: '/campaign/'+$route.params.id })">Back to campaign</button>

                    </div>
                    <div class="panel-body">
                        <select v-model="bulkAction">
                            <option value="" selected="">Select</option>
                            <option value="save">Save</option>
                        </select>
                        <button class="btn btn-approve btn-xs" @click="applyAction">apply</button>



                        <b-table ref="table" responsive hover :items="opportunities" :fields="fields" >
                                 <template slot="id" slot-scope="data">

                                <input type="checkbox" v-model="itemSelection" :value="data.item.id">
                            </template>

                            <template slot="name" slot-scope="data">

                                <p class="title-opportunity">{{data.item.name}}</p>
                                <a target="_blank" :href="data.item.url" class="url-opportunity">{{data.item.url}}</a>
                            </template>


                        </b-table>
                        <div class="loader" v-if="!dataLoaded"></div>
                        <p v-if="noResults" class="no-results">No Results!</p>



                    </div>
                </div>




            </div>



        </div>
        <!-- /#page-wrapper -->






    </div>

</template>

<style scoped>
    .title-opportunity{font-size: 12px;margin-bottom: 2px;}
    .domain-opportunity{font-weight: bold;margin-bottom: 1px;}

    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin: 50px auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .no-results{margin: 20px auto;width: fit-content;}

    .alert-success h3,.alert-danger h3{
        margin-top: 0;
        margin-top: 0;

    }
    .alert-success ul li,.alert-danger ul li{
        font-weight:bold;
        margin-top: 5px

    }

</style>

<script>
    import Vue from 'vue';
    import BootstrapVue from 'bootstrap-vue';

    Vue.use(BootstrapVue);
    export default {
        name: 'campaign',
        data() {
            return {
                noResults: false,
                campaignName: '',
                error: false,
                success: false,
                fields: [
                    {label: '', key: 'id', sortable: false},
                    {label: 'Opportunity', key: 'name', sortable: true},
                    {label: 'mozLinks', key: 'mozLinks', sortable: true},
                    {label: 'mozPA', key: 'mozPA', sortable: true},
                    {label: 'mozDA', key: 'mozDA', sortable: true},
                    {label: 'majesticLinks', key: 'majesticLinks', sortable: true}
                ],
                itemSelection: [],
                bulkAction: '',
                dataLoaded: false,
                opportunities: [],

            }
        },
        mounted() {
            this.getSearches();

            console.log(this.$route.params.id, this.id);
        },
        methods: {
            applyAction() {
                if (this.itemSelection)
                    if (this.bulkAction == 'save') {
                        axios.post("opportunities/favorite", {opportunities: this.itemSelection}).then(response => {
                            if (response.data.status == 200 && response.data.data != null) {
                                var i = this.opportunities.length;
                                this.success = [];
                                this.error = [];
                                while (i--) {
                                    let el = this.opportunities[i];
                                    if (response.data.data.favorites.includes(el.id)) {
                                        console.log('el.id,i');
                                        console.log(el.id, i);
                                        this.opportunities.splice(i, 1);
                                        this.success.push(el.domain);
                                    }
                                    if (response.data.data.notFound.includes(el.id)) {

                                        this.error.push(el.domain);
                                    }
                                }


                                this.success.forEach( (domain, index)=> {
                                    var i = this.opportunities.length;
                                    while (i--) {
                                        let el = this.opportunities[i];
                                        if (domain.indexOf(el.domain)) {

                                            axios.post("opportunities/delete", {opportunities: el.domain}).then(response => {
                                                if (response.status === 200 && response.data.data != null) {
                                                    this.opportunities.splice(i, 1);

                                                }
                                            })

                                            

                                        }
                                    }

                                });



                            }

                        });
                    }
            },
            getSearches() {
                axios.get("campaigns/searches/" + this.$route.params.id).then(response => {
                    console.log(response);
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data.data);
                        this.dataLoaded = true;
                        this.opportunities = response.data.data;
                        this.noResults = false;
                        if (!response.data.data.length)
                            this.noResults = true;


                    } else {

                    }

                }).catch(error => {
                    console.log(error);
                })


            },

        }
    }
</script>
