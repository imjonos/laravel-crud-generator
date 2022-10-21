<template>
    <input type="text" v-model="inputVal" v-on:change="$emit('change', $event.target.inputVal)" v-on:keypress="isNumber()" v-on:keyup="checkComma()" />
</template>
<script type="text/javascript">

export default {
    name: 'Number',
    props: {
        decimalPlaces: {
            default: 2
        },
        value :{
            default: ""
        },
        replaceFrom: {
            default: ","
        },
        replaceTo: {
            default: "."
        }
    },
    data(){
        return  {
            inputVal: (this.value)?parseFloat(this.value):"",
            isComma: false,
            isKeyPressed:false
        }
    },
    mounted: function(){
    },
    watch: {
        inputVal(val) {
            let result = String(val);
            result = result.replace(',', '.');
            this.$emit('input', parseFloat(result));
        }
    },
    methods: {
        isNumber: function() {
            const {event} = window;
            let evt = event;

            if(this.isKeyPressed) evt.preventDefault();;
            this.isKeyPressed = true;
            let number = String(this.inputVal);
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if (
                (charCode > 31 && (charCode < 48 || charCode > 57))
                && charCode !== this.replaceFrom.charCodeAt(0)
                && charCode !== this.replaceTo.charCodeAt(0)
                || (this.isComma && charCode === this.replaceTo.charCodeAt(0))
                || (number.indexOf(this.replaceTo)>0 && number.length-number.indexOf(this.replaceTo)>this.decimalPlaces)
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        checkComma: function() {
            let number = String(this.inputVal);
            if(number.indexOf(this.replaceFrom)>=0){
                if(number.indexOf(this.replaceTo)<0) number = number.replace(this.replaceFrom, this.replaceTo);
                else number = number.replace(this.replaceFrom, '');
            }
            this.inputVal = number;
            this.isComma = number.indexOf(this.replaceTo) >= 0;
            this.isKeyPressed = false;
            return true;
        }
    }
}
</script>
