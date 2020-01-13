<template>

</template>
<script>
    export default {
        name: 'crud-table',
        props : [

        ],
        components: {

        },
        data: function() {
            return {
            messages: [],
        };
    },
    updated: function () {

    },
    mounted: function() {
        this.getData();
    },
    methods: {
        getData: function(){
            axios.get("/users/"+this.id+"/crud")
            .then((response) => {
                this.messages = response.data.data;
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    },
};
</script>

<style lang="scss">
    @import './../../css/crud.css';
</style>