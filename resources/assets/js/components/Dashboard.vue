<template>



    <div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Messaging</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row"> 



            <!-- /.row -->
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-envelope fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">{{totalNewEmails}}</div>
                                        <div>New Emails!</div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-down"></i></span>
                                <div class="clearfix"></div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">


                        <div class="panel panel-default">
                            <div class="panel-heading">

                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date of first contact</th>
                                            <th>Sent via</th>
                                            <th>Recipient</th>
                                            <th>Opportunity</th>

                                            <th>Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for='(item, index) in emails'>
                                            <td>{{item.created_at}}</td>
                                            <td>{{item.email}}</td>
                                            <td>{{item.recipient}}</td>
                                            <td>{{item.opportunity}}</td>
                                            <td v-if="item.error"></td>
                                            <td v-else>
                                                <button class="btn btn-info btn-xs" @click="viewEmails(item, index)"><i class="fa fa-eye"></i> {{item.recipients.length}} View</button>

                                                <span style="margin-left:10px; color:red;"><i class="fa fa-envelope"></i> {{item.unseen}} New</span>


                                            </td>

                                        </tr>

                                    </tbody>
                                </table>

                                <div class="loader" v-if="!dataLoaded"></div>
                                <p v-if="noResults" class="no-results">No Results!</p>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-5">
                                <div class="panel panel-default" v-for='(item, index) in emails' v-if='item.display'>
                                    <div class="panel-heading">
                                        <i class="fa fa-envelope"></i> {{item.email}}
                                    </div>
                                    <!-- /.panel-heading -->
                                    <div class="panel-body">




                                        <div class="list-group" v-if="item.recipients.length">
                                            <a v-for="(email,n) in item.recipients" href="#" class="list-group-item" @click.prevent="displayEmailBody(email,item.email,item.id)">
                                                <i class="fa fa-envelope"></i> {{email.email}}
                                                <span class="pull-right text-muted small"><em>{{email.date}}</em>
                                                </span>
                                                <p style="margin-top: 8px;">{{email.subject}}</p>

                                            </a>

                                        </div>



                                    </div>
                                    <!-- /.panel-body -->
                                </div>
                            </div>

                            <div class="col-sm-7">
                                <div class="panel panel-default" v-if="selectedEmail.display">
                                    <div class="panel-heading">
                                        <b>Subject:</b> {{selectedEmail.subject}} 

                                        <button class="btn btn-success btn-xs pull-right" @click="replyEmail(selectedEmail.from,selectedEmail.to)" data-toggle="modal" data-target="#contactsModal"><i class="fa fa-mail-reply"></i> Reply</button>

                                    </div>
                                    
                                    <vue-markdown class="panel-body" :source="selectedEmail.body" ></vue-markdown>
                                    <!-- /.panel-body -->
                                </div>
                            </div>

                        </div>









                    </div>








                    <!-- /.panel-footer -->
                </div>
                <!-- /.panel .chat-panel -->

            </div>
            <!-- /.col-lg-4 -->

        </div>
        <!-- /#page-wrapper -->






        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="contactsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Send Email to: <b>{{replyTo}}</b></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="errorModal" class="alert alert-danger">
                                    {{errorModal}}
                                </div>
                                <div v-if="successModal" class="alert alert-success">
                                    {{ successModal }}
                                </div>
                                <p>From: <b>{{selectedEmail.from}}</b></p>

                                <input type="text" class="form-control email-subject" v-model="subject" placeholder="Subject" style="margin-bottom: 10px;">
                                <textarea rows="22" class="form-control text-contact email-content" v-model="templateText">
                                    
                                </textarea>

                            </div>


                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="sendEmail()">Send Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
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

</style>

<script>
    import VueMarkdown from 'vue-markdown';
    export default {
        components: {
            'vue-markdown': VueMarkdown
        },
        data() {
            return {
                emails: [],
                replyTo: '',
                errorModal: '',
                successModal: '',
                templateText: '',
                subject: '',
                replyToIndex: 0,
                totalNewEmails: 0,
                selectedEmail: {
                    display: false,
                    subject: '',
                    body: '',
                    from: '',
                    to: ''

                },
                dataLoaded: false,
                noResults: false,

            }
        },
        created() {
            this.getEmails();
        },
        methods: {
            displayEmailBody(email, from, fromId) {
                this.selectedEmail.display = true;
                this.selectedEmail.body = email.body;
                this.selectedEmail.subject = email.subject;
                this.selectedEmail.from = from;
                this.selectedEmail.id = fromId;
                this.selectedEmail.to = email.email;
                window.scroll({top: 0, left: 0, behavior: 'smooth'});
            },
            replyEmail(item, index) {
                this.replyTo = this.selectedEmail.to;
                //this.replyToIndex = index;
                this.subject = this.selectedEmail.subject;
                this.templateText = '';
                this.errorModal = '';
                this.successModal = '';

            },
            sendEmail() {
                this.errorModal = false;
                this.successModal = false;

                if (this.replyTo.length && this.templateText.length && this.subject.length) {
                    axios.post("emails/reply-email", {text: this.templateText, subject: this.subject, email: this.replyTo, imapAccount: this.selectedEmail.fromId}).then(response => {
                        if (response.data.status == 200) {
                            this.successModal = 'Emails Sent!';
                            //this.emails[this.replyToIndex].data.push({type: 'outbox', subject: this.subject, body: this.templateText});
                            this.subject = '';
                            this.templateText = '';
                            this.getEmails();

                        } else {

                            this.errorModal = 'Error, try again!';
                        }
                    });
                } else {
                    alert('Add some text and recipients!');
                }
            },
            viewEmails(item, index) {
                this.emails.forEach((el) => {
                    el.display = false;
                });
                this.emails[index].display = true;
                this.selectedEmail.display = false;

            },
            getEmails() {
                this.emails = [];
                axios.get("emails").then(response => {
                    if (response.data.status == 200 && response.data.data != null) {

                        this.emails = response.data.data;
                        console.log('this.emails');
                        console.log(this.emails);
                        this.emails.forEach((el, i) => {
//                            console.log(i);
                            this.totalNewEmails += el.unseen;
//                            console.log((this.emails[i]));
//
//
//                            this.emails[i].data.forEach((el) => {
//                                if (el.type == 'inbox')
//                                    this.setLastSubject(el.subject, i);
//
//                            });

                        });
                        console.log(this.emails);
                        this.dataLoaded = true;
                        this.noResults = false;
                        if (!response.data.data.length)
                            this.noResults = true;

                    }

                }).catch(error => {
                    console.log(error);
                })


            },
            setLastSubject(subject, i) {
                this.emails[i].lastSubject = subject;

            }

        }
    }
</script>
