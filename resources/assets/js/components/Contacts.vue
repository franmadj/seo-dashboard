<template>

    <div>
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Messaging</a></li>
                        <li class="breadcrumb-item"><a href="/campaigns">Campaigns</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Contacts</li>
                    </ol>
                </nav>
                <h1 class="page-header">Contacts</h1>
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

                    </div>
                    <div class="panel-body">
                        <select v-model="bulkAction">
                            <option value="" selected="">Select</option>

                            <option value="delete">Delete</option>
                        </select>
                        <button class="btn btn-approve btn-xs" @click="applyAction">apply</button>



                        <b-table ref="table" responsive hover :items="favorites" :fields="fields" >
                                 <template slot="id" slot-scope="data">

                                <input type="checkbox" v-model="itemSelection" :value="data.item.id">
                            </template>

                            <template slot="contactsCount" slot-scope="data">
                                <button class="btn btn-success btn-xs" @click="viewContacts(data.item.id,data.index)" data-toggle="modal" data-target="#contactsModal">Contact {{data.item.contactsCount}}</button>


                            </template>


                        </b-table>
                        <div class="loader" v-if="!dataLoaded"></div>
                        <p v-if="noResults" class="no-results">No Results!</p>



                    </div>
                </div>




            </div>



        </div>
        <!-- /#page-wrapper -->




        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="contactsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="width:75%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Contacts for <b>{{campaign}}</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-7 input-data-email">
                                <div v-if="errorModal" class="alert alert-danger">
                                    {{errorModal}}
                                </div>
                                <div v-if="successModal" class="alert alert-success">
                                    {{ successModal }}
                                </div>
                                <div class="templates-content">
                                    <h3>Templates</h3>

                                    <select class="form-control" v-if="imaps.length" v-model="imapAccount">
                                        <option value="" selected="">Select Imap Account</option>
                                        <option v-for="(item, index) in imaps" :value="item.id">{{item.domain}}</option>

                                    </select>

                                    <select class="form-control" v-if="templates.length" @change="setTemplate($event)">
                                        <option value="" selected="">Select Teamplate</option>
                                        <option v-for="(item, index) in templates" :value="index">{{item.name}}</option>

                                    </select>
                                    <p v-else>No Temaplates</p>

                                </div>
                                <input type="text" class="form-control email-subject" v-model="subject" placeholder="Subject">
                                <textarea rows="22" class="form-control text-contact email-content" id="email-content" v-model="templateText">       
                                    
                                </textarea>

                            </div>
                            <div class="col-md-5">
                                <div class="contact-group">
                                    <ul class="list-group">
                                        <li class="list-group-item contact-item" v-for="(item,ix) in contactsItems" v-if="contactsItems" >
                                            <input type="checkbox" v-model="recipients[ix]" :value="item.id+','+item.value">
                                            <div v-html="contactDetails(item,ix)"></div>


                                        </li>


                                    </ul>
                                </div>


                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="sendEmails()">Send Emails</button>
                    </div>
                </div>
            </div>
        </div>






    </div>

</template>

<style>
    .title-opportunity{font-size: 12px;margin-bottom: 2px;}
    .domain-opportunity{font-weight: bold;margin-bottom: 1px;}
    .contact-group input[type="checkbox"]{
        position: absolute;
        right: 10px;
    }
    .contact-group{
        padding-right: 10px;
        max-height: 550px;
        overflow-y: scroll;
    }
    .contact-item{
        display: block;
        margin-bottom: 10px;
    }

    .contact-item span{
        margin-right: 3px;
        top: 3px;
    }

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

    .text-contact{width: 100%;}
    .email-content{
        padding: 12px;
    }
    ul.list-templates{
        padding-left: 15px;
        margin-top: 10px;
    }
    .templates-content h3{
        margin-top: 0px;

    }
    .templates-content{
        margin-bottom:  12px;

    }
    .input-data-email input, .input-data-email select{
        margin-bottom: 15px;
    }

</style>

<script>
    import Vue from 'vue';
    import BootstrapVue from 'bootstrap-vue';

    Vue.use(BootstrapVue);

//import tinymce from 'vue-tinymce-editor'
//Vue.component('tinymce', tinymce)

    export default {
        name: 'contact',

        data() {
            return {
                description: '',
                noResults: false,
                campaignName: '',
                error: false,
                success: false,
                errorModal: false,
                successModal: false,
                fields: [
                    {label: '', key: 'id', sortable: false},
                    {label: 'Opportunity', key: 'domain', sortable: true},
                    {label: 'Campaign', key: 'campaign', sortable: true},
                    {label: 'Messaged', key: 'messaged', sortable: true},
                    {label: 'Contacts', key: 'contactsCount', sortable: true},
                ],
                itemSelection: [],
                bulkAction: '',
                dataLoaded: false,
                favorites: [],
                contactsItems: [],
                campaign: '',
                templates: [],
                templateText: '',
                subject: '',
                recipients: [],
                imaps: [],
                imapAccount: ''

            }
        },
        mounted() {
            this.getFavorites();
            this.getTemplates();
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
            setTemplate(template) {
                if (template.target.value) {
                    this.templateText = this.templates[template.target.value].text;
                    this.subject = this.templates[template.target.value].subject;
                }
                console.log(template.target.value);
                //this.templateText = template.text;

            },
            getTemplates() {
                axios.get("templates").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data);
                        this.templates = response.data.data;
                    } else {
                    }
                }).catch(error => {
                    console.log(error);
                })
            },
            sendEmails() {
                this.errorModal = false;
                this.successModal = false;
                if(!this.imapAccount){
                    this.errorModal = 'Select Imap Account!';
                    return false;
                }
                let emailsToSend = [];
                console.log(this.recipients);
                this.recipients.forEach((el, i) => {
                    if (el)
                        emailsToSend.push(this.contactsItems[i].valueId);
                    console.log(this.contactsItems[i].valueId);
                });
                if (emailsToSend.length && this.templateText.length && this.subject.length) {
                    axios.post("send-emails", {text: jQuery('#email-content').val(), subject: this.subject, emails: emailsToSend, imapAccount: this.imapAccount}).then(response => {
                        if (response.data.status == 200) {
                            this.successModal = 'Emails Sent!';
                        } else {
                            this.errorModal = 'Error, try again!';
                        }
                    });
                } else {
                    this.errorModal = 'Add some text and recipients!';
                }
            },
            contactDetails(data, index) {

                let html = '';
                if (data.first_name)
                    html += `<span class="contact-item"><b>${data.first_name} ${data.last_name}</b> </span>`;
                if (data.value)
                    html += `<span class="contact-item"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> ${data.value} </span>`;

                if (data.phone_number)
                    html += `<span class="contact-item"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> ${data.phone_number} </span>`;

                return html;
            },

            viewContacts(id, i) {
                //console.log(this.favorites[i]);
                this.campaign = this.favorites[i].campaign;
                if (this.favorites[i].contacts.length) {
                    this.favorites[i].contacts.forEach((el, i) => {
                        let data = JSON.parse(el.contact_data);
                        this.contactsItems[i] = {id: el.id, valueId: el.id + ',' + data.value, value: data.value, first_name: data.first_name, last_name: data.last_name, phone_number: data.phone_number};
                    });
                } else {
                    this.contactsItems = [];
                }


            },
            removeRows(data) {

                let i = this.favorites.length;
                while (i--) {
                    let el = this.favorites[i];
                    if (data.includes(el.id)) {
                        console.log('el.id,i');
                        console.log(el.id, i);
                        this.favorites.splice(i, 1);
                    }
                }

            },
            applyAction() {
                if (this.itemSelection) {
                    if (this.bulkAction == 'save') {
                        axios.post("opportunities/favorite", {opportunities: this.itemSelection}).then(response => {
                            if (response.data.status == 200 && response.data.data != null) {
                                this.removeRows(response.data.data.favorites);

//                                
//                                var i = this.opportunities.length;
//                                while (i--) {
//                                    let el = this.opportunities[i];
//                                    if (response.data.data.favorites.includes(el.id)) {
//                                        console.log('el.id,i');
//                                        console.log(el.id, i);
//                                        this.opportunities.splice(i, 1);
//                                    }
//                                }
                            }

                        });
                    } else if (this.bulkAction == 'delete') {
                        if (confirm('Are you sure you want to delete?')) {
                            axios.post("opportunities/delete", {opportunities: this.itemSelection}).then(response => {
                                if (response.status === 200 && response.data.data != null) {
                                    this.removeRows(response.data.data);
                                    this.success = "Deleted successfully !";

                                }
                            })
                        }

                    }
                }

            },
            getFavorites() {
                axios.get("opportunities/favorites").then(response => {
                    console.log(response);
                    if (response.data.status == 200 && response.data.data != null) {
                        console.log(response.data.data);
                        this.dataLoaded = true;
                        this.favorites = response.data.data;
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
