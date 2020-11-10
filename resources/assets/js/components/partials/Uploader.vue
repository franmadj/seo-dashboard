<template>
    <button class="btn btn-approve" style="margin-bottom: 15px;" type="file" id="file" ref="file" v-on:change="handleFileUpload()">Import Excluded Domains</button>
    <input type="file" class="input-file" ref="file" v-on:change="handleFileUpload()"/>
</template>

<script>
    export default {
        props: ['token'],
        data() {
            return {
                file: ''
            }
        },

        created() {
            this.UserName = userName;
        },
        methods: {
            handleFileUpload() {
                this.file = this.$refs.file.files[0];

            },
            submitFile() {
                let formData = new FormData();
                formData.append('file', this.file);
                axios.post('/single-file',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                ).then(function () {
                    console.log('SUCCESS!!');
                })
                        .catch(function () {
                            console.log('FAILURE!!');
                        });

            },
        }
    }
</script>
