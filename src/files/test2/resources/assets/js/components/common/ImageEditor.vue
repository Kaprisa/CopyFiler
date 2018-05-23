<template>
    <div style="height: 95vh;display: flex; justify-content: center; align-items: center;">
        <canvas ref="editor"></canvas>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editor: null
            }
        },
        mounted() {
            const canvas = this.$refs.editor
            const ctx = canvas.getContext("2d")
            this.editor=canvas
            const image = new Image()
            image.onload = () => {
                canvas.width = image.naturalWidth
                canvas.height = image.naturalHeight
                ctx.drawImage(image, 0, 0)
                ctx.drawImage(image, 0, 0, image.naturalWidth, image.naturalHeight)
                var imgData = ctx.getImageData(0, 0, this.editor.width, this.editor.height),
                    pxData = imgData.data,
                    length = pxData.length;
                for(var x = 0; x < length; x+=4) {
                    var r = pxData[x],
                        g = pxData[x + 1],
                        b = pxData[x + 2],
                        grey = (r/255)*(g/255)*255;
                    pxData[x] = b;
                    pxData[x + 1] = r;
                    pxData[x + 2] = g;
                }
                //paint grayscale image back
                ctx.putImageData(imgData, 0, 0);
            }
            image.src = '/images/bg1.jpg';
        },
        methods: {

        }
    }
</script>