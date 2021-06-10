<template>
    <div>
        <form @submit.prevent="submit" v-if="isAuthenticated">

            <div class="card-body">
                <textarea v-model="body"
                          class="form-control border-0 bg-light"
                          name="body"
                          required
                          :placeholder="`¿Qué estas pensando ${currentUser.name}?`">

                </textarea>
            </div>
            <div class="card-footer">
                <button class="btn btn-info" id="create-status"><i class="fas fa-paper-plane mr-1"></i>  Publicar</button>
            </div>
        </form>
        <div v-else class="card-body">
            <a href="/login">Si deseas participar,Logeate</a>
        </div>
    </div>
</template>

<script>

    export default {
        data(){
            return{
                body:'',
            }
        },



        methods:{
            submit(){
                axios.post('/statuses', {body: this.body})

                .then(res => {
                    EventBus.$emit('status-created', res.data.data);
                    this.body = ''
                })

                    .catch(err => {
                        console.log(err.response.data);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
