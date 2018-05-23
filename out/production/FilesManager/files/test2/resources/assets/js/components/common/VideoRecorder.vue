<template>
    <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition" :overlay="false">
        <v-btn color="primary" dark slot="activator">Записать видео</v-btn>
        <v-card>
            <video ref="preview" width="300" height="300" autoplay muted controls></video>
            <video ref="recording" width="300" height="300" controls></video>
            <v-card-actions>
                <v-btn @click="record">record</v-btn>
                <a :href="src" download="video.webm">download</a>
                <v-btn @click="stop($refs.preview.srcObject);">stop</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: 'v-recorder',
        data() {
            return {
                src: '',
                dialog: false
            }
        },
        methods: {
            start(stream, lengthInMS) {
                let recorder = new MediaRecorder(stream);
                let data = [];

                recorder.ondataavailable = event => data.push(event.data);
                recorder.start();

                let stopped = new Promise((resolve, reject) => {
                    recorder.onstop = resolve;
                    recorder.onerror = event => reject(event.name);
                });

                let recorded = this.wait(lengthInMS).then(
                    () => recorder.state === "recording" && recorder.stop()
                );

                return Promise.all([
                    stopped,
                    recorded
                ])
                    .then(() => data);
            },
            wait(delayInMS) {
                return new Promise(resolve => setTimeout(resolve, delayInMS));
            },
            record() {
                navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                }).then(stream => {
                    this.$refs.preview.srcObject = stream;
                    this.src = stream;
                    this.$refs.preview.captureStream = this.$refs.preview.captureStream || this.$refs.preview.mozCaptureStream;
                    return new Promise(resolve => this.$refs.preview.onplaying = resolve);
                }).then(() => this.start(this.$refs.preview.captureStream(), 5000))
                    .then (recordedChunks => {
                        let recordedBlob = new Blob(recordedChunks, { type: "video/webm" });
                        this.$refs.recording.src = URL.createObjectURL(recordedBlob);
                        this.src = this.$refs.recording.src;
                    })
                    .catch();
            },
            stop() {
                this.$refs.preview.srcObject.getTracks().forEach(track => track.stop());
            }
        }
    }
</script>