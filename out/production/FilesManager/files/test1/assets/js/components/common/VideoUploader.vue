<template>
    <v-dialog v-model="dialog" width="60vw">
        <v-btn dark color="accent" slot="activator">Видео</v-btn>
        <v-card>
            <form class="card" enctype="multipart/form-data">
                <input :style="`height: ${uploaded || fileName ? '50%' : '100%'}`" type="file" :name="name" accept="video/mp4" :disabled="disabled" @change="filesChange($event.target.name, $event.target.files[0])"
                       class="input-file">
                <v-card-title>{{ label }}</v-card-title>
                <div class="layout justify-center">
                    <video v-if="fileName || uploaded" width="300" height="300" controls>
                        <source :src="`/video/${dir}/${uploaded ? uploaded : fileName}`" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                    <v-icon large v-else color="gray">videocam</v-icon>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn icon :loading="disabled">
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
        name: 'photo-uploader',
        data() {
            return {
                uploaded: this.src,
                uploadError: null,
                currentStatus: STATUS_INITIAL,
                name: 'video',
                snackbar: false,
                text: '',
                dialog: false
            }
        },
        computed: {
            isInitial() {
                return this.currentStatus === STATUS_INITIAL
            },
            disabled() {
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
                axios.post(`/api/video/${this.dir}`, formData)
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

<style lang="scss">
    .input-file {
        opacity: 0;
        //width: 100%;
        //height: 100%;
        top: 0;
        left: 0;
        right: 0;
        height: 100%;
        position: absolute;
        cursor: pointer;
        z-index: 5;
    }
</style>
