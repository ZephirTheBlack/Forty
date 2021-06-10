<template>
    <button :class="getBtnClasses" @click="toggle()">
        <i :class="getIconClasses"></i>
        {{getText}} </button>

</template>

<script>
    export default {
        props:{
            model:{
                type:Object,
                required: true
            },
            url:{
              type: String,
              required: true
            },
        },
        computed:{
            getText(){
                return this.model.is_liked ? 'Te Gusta' : 'Me Gusta';
            },
            getBtnClasses(){

                return[
                    this.model.is_liked ? 'font-weight-bold' : '', 'btn', 'btn-link', 'btn-sm' ,'font-weight-bold']
            },

            getIconClasses(){

                return[
                    this.model.is_liked ? 'fas' : 'far', 'fa-thumbs-up', 'text-primary' ,'mr-1']
            }
        },
        methods:{
            toggle(){
                let method = this.model.is_liked ? 'delete' : 'post';

                axios[method](this.url)
                    .then(res=>{
                        this.model.is_liked = !this.model.is_liked;
                        this.model.likes_count = res.data.likes_count;
                    })
            },
        }
    }
</script>

<style lang="scss" scoped>
    .comments-like-btn{
        font-size: 0.8em;
        padding-left: 0;
        i { display: none}
    }
</style>
