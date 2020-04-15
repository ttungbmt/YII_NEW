<template>
    <div>
        <div class="font-medium mb-1">Biểu thức</div>
        <div>
            <button type="button" class="btn btn-opr" @click="onClick('+')">+</button>
            <button type="button" class="btn btn-opr" @click="onClick('sqrt')">sqrt</button>
            <button type="button" class="btn btn-opr" @click="onClick('cos')">cos</button>
            <button type="button" class="btn btn-opr" @click="onClick('sin')">sin</button>
            <button type="button" class="btn btn-opr" @click="onClick('tan')">tan</button>
            <button type="button" class="btn btn-opr" @click="onClick('log10')">log10</button>
            <button type="button" class="btn btn-opr" @click="onClick('(')">(</button>
            <button type="button" class="btn btn-opr" @click="onClick('-')">-</button>
            <button type="button" class="btn btn-opr" @click="onClick('/')">/</button>
            <button type="button" class="btn btn-opr" @click="onClick('^')">^</button>
            <button type="button" class="btn btn-opr" @click="onClick('acos')">acos</button>
            <button type="button" class="btn btn-opr" @click="onClick('asin')">asin</button>
            <button type="button" class="btn btn-opr" @click="onClick('atan')">atan</button>
            <button type="button" class="btn btn-opr" @click="onClick('ln')">ln</button>
            <button type="button" class="btn btn-opr" @click="onClick(')')">)</button>
            <button type="button" class="btn btn-opr" @click="onClick('<')"><</button>
            <button type="button" class="btn btn-opr" @click="onClick('=')">=</button>
            <button type="button" class="btn btn-opr" @click="onClick('!=')">!=</button>
            <button type="button" class="btn btn-opr" @click="onClick('<=')"><=</button>
            <button type="button" class="btn btn-opr" @click="onClick('>=')">>=</button>
            <button type="button" class="btn btn-opr" @click="onClick('AND')">AND</button>
            <button type="button" class="btn btn-opr" @click="onClick('OR')">OR</button>
            <button type="button" class="btn btn-opr" @click="onClick('abs')">abs</button>
            <button type="button" class="btn btn-opr" @click="onClick('min')">min</button>
            <button type="button" class="btn btn-opr" @click="onClick('max')">max</button>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['onClick'],
    }
</script>