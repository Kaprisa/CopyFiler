<template>
    <div class="container">
        <form ref="form" class="form card elevation-5" enctype="multipart/form-data">
            <input
                    type="file"
                    multiple
                    :name="name"
                    :disabled="loading"
                    @change="save()"
                    class="input-file"
                    :style="`height: ${this.inputHeight}`"
            >
            <v-card-title>{{ label }}</v-card-title>
            <div class="layout justify-center">
                <v-list two-line v-if="files.length > 0">
                    <v-list-tile style="z-index: 10;" v-for="item in files" :key="item.name" avatar>
                        <v-list-tile-avatar>
                            <v-icon dark :class="colors[Math.floor(Math.random()*(colors.length))]">description</v-icon>
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.originalName }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ Math.floor(item.size / 1024) }}Kb</v-list-tile-sub-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-icon style="cursor: pointer;" @click="remove(item)">delete</v-icon>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
                <v-icon large v-else color="gray">attach_file</v-icon>
            </div>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="$refs.form[name].click()" icon :loading="loading">
                    <v-icon color="primary">cloud_upload</v-icon>
                </v-btn>
            </v-card-actions>
        </form>
        <v-snackbar
                :timeout="6000"
                v-model="snackbar"
                :color="success ? 'success' : 'error'"
        >
            {{ text }}
            <v-btn dark icon flat @click.native="snackbar = false"><v-icon>close</v-icon></v-btn>
        </v-snackbar>
    </div>
</template>

<script>
    export default {
        name: 'files-uploader',
        data() {
            return {
                colors: ['blue', 'pink', 'yellow', 'purple', 'green'],
                files: [],
                name: 'photos[]',
                snackbar: false,
                loading: false,
                text: '',
                hello: null,
                success: true
            }
        },
        computed: {
            inputHeight() {
               return this.files.length > 0 ? 0 : '100%'
            }
        },
        props: {
            label: String,
            fileName: String,
            dir: 'files'
        },
        methods: {
            save() {
                const formData = new FormData(this.$refs.form)
                this.loading = true
                axios.post(`/file/${this.dir}/upload`, formData)
                    .then(res => {
                        this.files = this.files.concat(res.data)
                        this.$emit('uploaded', this.files)
                        this.loading = false
                        this.text = 'Файлы упешно загружены'
                        this.snackbar = true
                        this.success = true
                    })
                    .catch(err => {
                        this.text = 'Не удалось загрузить файлы'
                        this.loading = false
                        this.snackbar = true
                        this.success = false
                    })
            },
            remove(file) {
                this.files.splice(this.files.indexOf(file), 1)
                axios.post(`/file/${this.dir}/remove`, { name: file.name })
            }
        },
    }
</script>

<style lang="scss">
    .input-file {
        opacity: 0;
        width: 100%;
        height: 100%;
        position: absolute;
        cursor: pointer;
        z-index: 5;
    }
</style>
