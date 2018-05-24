<template>
    <path
            :d="`
                M ${x - width * 0.02 + width / 2} ${open}
                L ${x - width * 0.02 + width / 2} ${low}
                L ${x + width * 0.02 + width / 2} ${low}
                L ${x + width * 0.02 + width / 2} ${open}
                M ${x - width * 0.02 + width / 2} ${close}
                L ${x - width * 0.02 + width / 2} ${high}
                L ${x + width * 0.02 + width / 2} ${high}
                L ${x + width * 0.02 + width / 2} ${close}
                M ${x} ${open}
                L ${x + width} ${open}
                L ${x + width} ${close}
                L ${x} ${close}
                L ${x} ${open}
            `"
            :fill="close < open ? raising_color : falling_color"
    ></path>
</template>

<script>
    export default {
        name: 'candle',
        props: {
            open: Number,
            high: Number,
            low: Number,
            close: Number,
            raising_color: {
                type: String,
                default: '#76FF03'
            },
            falling_color: {
                type: String,
                default: '#fc071b'
            },
            x: Number,
            width: Number
        },
    }
</script>