<template>
    <v-dialog v-model="dialog" width="30vw">
        <v-btn dark color="accent" slot="activator">Фото</v-btn>
        <v-card>
            <form class="card" enctype="multipart/form-data">
                <input type="file" name="photos" accept="image/*" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files[0])"
                        class="input-file">
                <v-card-title>{{ label }}</v-card-title>
                <div class="layout justify-center">
                    <img v-if="fileName || uploaded" :src="`/uploads/${dir}/${uploaded ? uploaded.name : fileName}`" height="150px">
                    <v-icon large v-else color="gray">add_a_photo</v-icon>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn icon :loading="isSaving">
                        <v-icon color="primary">cloud_upload</v-icon>
                    </v-btn>
                </v-card-actions>
            </form>
            <v-snackbar
                    :timeout="6000"
                    :color="isSuccess ? 'success' : 'error'"
                    v-model="snackbar"
            >
                {{ text }}
                <v-btn dark icon flat @click.native="snackbar = false"><v-icon>close</v-icon></v-btn>
            </v-snackbar>
            <v-card-actions>
                <v-spacer/>
                <v-btn color="amber" dark @click="dialog = false">Ок</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>

    const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_SUCCESS = 2, STATUS_FAILED = 3

    export default {
        dir: 'photo-uploader',
        data() {
            return {
                uploaded: this.src,
                uploadError: null,
                currentStatus: STATUS_INITIAL,
                snackbar: false,
                text: '',
                dialog: false
            }
        },
        computed: {
            isInitial() {
                return this.currentStatus === STATUS_INITIAL
            },
            isSaving() {
                return this.currentStatus === STATUS_SAVING
            },
            isSuccess() {
                return this.currentStatus === STATUS_SUCCESS
            },
            isFailed() {
                return this.currentStatus === STATUS_FAILED
            }
        },
        props: {
            label: String,
            fileName: String,
            dir: String
        },
        methods: {
            save(formData) {
                this.currentStatus = STATUS_SAVING
                axios.post(`/api/files/${this.dir}/upload`, formData)
                    .then(res => {
                        if (this.uploaded) {
                            axios.post('/api/files/photos/remove', { name: this.uploaded })
                        }
                        this.uploaded = res.data
                        this.$emit('uploadFile', this.uploaded)
                        this.currentStatus = STATUS_SUCCESS
                        this.text = 'Файл упешно загружен'
                        this.snackbar = true
                    })
                    .catch(err => {
                        this.uploadError = err.response;
                        this.currentStatus = STATUS_FAILED;
                        this.text = 'Не удалось загрузить файл'
                        this.snackbar = true
                    });
            },
            filesChange(fieldName, file) {
                const formData = new FormData()
                if (!file) return
                formData.append(fieldName, file, file.name)
                this.save(formData)
            }
        },
    }
</script>
