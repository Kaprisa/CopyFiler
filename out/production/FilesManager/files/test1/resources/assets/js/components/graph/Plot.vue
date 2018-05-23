<template>
    <v-card :style="`margin-bottom: 2vh; width: 100%; height: ${height}vh; cursor: pointer;`">
        <div
                class="tooltip__content menuable__content__active"
                :style="`position: absolute; top: ${100 - currentY + 2}%; left: ${currentX + 2}%;`"
                v-if="currentX !== 0 && currentY !== 0 && current"
        >
            <div>
                    <span v-for="(v, k) in current" :key="k">
                        {{ k | capitalize }}: {{ v }}
                        <br>
                    </span>
            </div>
        </div>
        <div
                v-if="currentX !== 0"
                class="tooltip__content menuable__content__active"
                :style="`position: absolute; bottom: 0; left: ${currentX}%; transform: translateX(-50%);`"
        >
            {{ displayX }}
        </div>
        <div
                v-if="currentY !== 0"
                class="tooltip__content menuable__content__active"
                :style="`position: absolute; bottom: ${currentY}%; transform: translateY(50%); left: 0;`"
        >
            {{ displayY }}
        </div>
        <span :style="`font-size: 15px; color: #000000; font-weight: bold; position: absolute; top: 50%; left: 50%;`">0</span>
        <svg
                width="100%"
                height="100%"
                ref="plot"
                viewBox="0 0 100 100"
                preserveAspectRatio="none"
        >
            <line
                    x1="50"
                    :y1="0"
                    x2="50"
                    :y2="100"
                    stroke-width="0.1"
                    :stroke="axesColor"
            ></line>
            <line
                    :x1="0"
                    y1="50"
                    :x2="100"
                    y2="50"
                    stroke-width="0.1"
                    :stroke="axesColor"
            ></line>
            <line
                    x1="0"
                    :y1="toPercents(zero)"
                    x2="100"
                    :y2="toPercents(zero)"
                    stroke-width="0.1"
                    stroke="#000000"
            ></line>
            <line
                    v-for="i in [0, 1, 2, 3, 4, 5]"
                    :x1="i * (100 / 6)"
                    y1="0"
                    :x2="i * (100 / 6)"
                    y2="100"
                    stroke-width="0.02"
                    :stroke="axesColor"
            ></line>
            <line
                    v-for="i in [0, 1, 2, 3, 4, 5]"
                    x1="0"
                    :y1="100 - i * (100 / 6)"
                    x2="100"
                    :y2="100 - i * (100 / 6)"
                    stroke-width="0.05"
                    :stroke="axesColor"
            ></line>
            <line
                    x1="0"
                    :y1="100 - currentY"
                    x2="100"
                    :y2="100 - currentY"
                    stroke-width="0.1"
                    :stroke="axesColor"
            ></line>
            <line
                    :x1="currentX"
                    y1="0"
                    :x2="currentX"
                    y2="100"
                    stroke-width="0.05"
                    :stroke="axesColor"
            ></line>
            <path
                    stroke-width="0.1"
                    stroke="blue"
                    fill="transparent"
                    :d="path"
            ></path>
        </svg>
    </v-card>
</template>

<script>

    export default {
        name: 'plot',
        data() {
            return {
                zero: 0,
                currentX: 0,
                currentY: 0,
                coordinates: [],
                axesColor: '#9494b8',
                minX: 0,
                maxX: 0,
                range: 0,
                current: null,
                show: true,
                displayX: 0,
                displayY: 0,
                path: 'M 0 90'
            }
        },
        props: {
            type: String,
            params: {
                type: Object,
                default: null
            },
            height: {
                type: Number,
                default: 90
            },
            mouse: {
                type: Object,
                default: null
            }
        },
        mounted() {
           this.linear()
        },
        computed: {},
        watch: {
            mouse(val) {
                const rect = this.$refs.plot.getBoundingClientRect()
                const a = val.y - rect.top
                const b = val.x - rect.left
                if (a > 0 && a < rect.height) {
                    this.currentY = 100 - Math.floor((a * 100) / rect.height)
                    this.currentX = Math.floor((b * 100) / rect.width)
                    this.displayX = this.params.min + (Math.ceil(this.currentX * (this.params.max - this.params.min)) / 100)
                    this.displayY = this.params.min + (Math.ceil(this.currentY * (this.params.max - this.params.min)) / 100)
                } else {
                    this.currentX = 0
                    this.currentY = 0
                }
            },
        },
        methods: {
            toPercents(val) {
                return 100 - (val * 100) / (this.params.max - this.params.min)
            },
            linear() {
                let path = 'M 0 90 '
                for (let i = this.params.min; i < this.params.max; i++) {
                    path += `L ${this.toPercents(i)} ${this.toPercents(i)} `
                }
                this.path = path
            }
        }
    }
</script>