<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>日記一覧</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <diaryboard></diaryboard>
</template>

<script>
import diaryboard from './Diaryboard.vue';
export default {
    name:"dashboard",
    components:{
        diaryboard,
    },
    data(){
        return {
            user:this.$store.state.auth.user
        }
    },
    methods: {

    },
}
</script>