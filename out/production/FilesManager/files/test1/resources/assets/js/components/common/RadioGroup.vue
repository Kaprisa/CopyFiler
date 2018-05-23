<template>
   <!--template <div>-->
        <!--<div>-->
            <!--<label>-->
                <!--<input type="checkbox" class="option-input checkbox" v-model="values" />-->
                <!--Checkbox-->
            <!--</label>-->
            <!--<label>-->
                <!--<input type="checkbox" class="option-input checkbox" v-model="values"/>-->
                <!--Checkbox-->
            <!--</label>-->
            <!--<label>-->
                <!--<input type="checkbox" class="option-input checkbox" v-model="values"/>-->
                <!--Checkbox-->
            <!--</label>-->
        <!--</div>-->
        <div v-if="q.answers && q.answers.length">
            <label @click="hello" v-for="a,i in q.answers" :key="`a${i}`">
                <input type="radio" class="option-input radio"/>
                <span v-html="a.text"></span>
            </label>
            <!--<label>-->
                <!--<input type="radio" class="option-input radio" v-model="value"/>-->
                <!--Radio option-->
            <!--</label>-->
            <!--<label>-->
                <!--<input type="radio" class="option-input radio" v-model="value"/>-->
                <!--Radio option-->
            <!--</label>-->
        </div>
    <!--</div>-->
</template>

<script>
    export default {
        data() {
            return {
                values: [],
                value: null
            }
        },
        methods: {
            hello() {
                console.log(this.q.answers)
            }
        },
        props: {
            q: Object
        }
    }
</script>

<style>
    *{font-family: 'Roboto', sans-serif;}

    @keyframes click-wave {
        0% {
            height: 20px;
            width: 20px;
            opacity: 0.35;
            position: relative;
        }
        100% {
            height: 150px;
            width: 150px;
            margin-left: -60px;
            margin-top: -60px;
            opacity: 0;
        }
    }

    .option-input {
        appearance: none;
        position: relative;
        top: 10px;
        right: 0;
        bottom: 0;
        left: 0;
        height: 30px;
        width: 30px;
        transition: all 0.15s ease-out 0s;
        background: #cbd1d8;
        border: none;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        margin-right: 0.5rem;
        outline: none;
        z-index: 1000;
    }
    .option-input:hover {
        background: #9faab7;
    }
    .option-input:checked {
        background: #40e0d0;
    }
    .option-input:checked::before {
        height: 30px;
        width: 30px;
        position: absolute;
        content: '✔';
        display: inline-block;
        font-size: 20px;
        text-align: center;
        line-height: 30px;
    }
    .option-input:checked::after {
        -webkit-animation: click-wave 0.65s;
        -moz-animation: click-wave 0.65s;
        animation: click-wave 0.65s;
        background: #40e0d0;
        content: '';
        display: block;
        position: relative;
        z-index: 100;
    }
    .option-input.radio {
        border-radius: 50%;
    }
    .option-input.radio::after {
        border-radius: 50%;
    }
</style>