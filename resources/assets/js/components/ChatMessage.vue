<template>
    <div class="chat-message" v-if="message">
        <div class="py-3 mr-3 float-left">
            <div class="avatar">
                <gravatar class="img-avatar" :hash="message.hash" :alt="message.user.name"/>
                <span class="avatar-status badge-success"></span>
            </div>
        </div>
        <div v-if="message">
            <small class="text-muted">{{ message.user.name }}</small>
            <small class="text-muted float-right mt-1">{{ message.created_at | moment("hh:mm A") }}</small>
        </div>
        <small class="text-muted">{{ message.text }}</small>
        <hr>
    </div>
</template>

<script>
    import Gravatar from 'vue-gravatar';

    export default {
        props: [
            'message'
        ],
        components: {
            Gravatar,
        }
    }
</script>

<style lang="css">
</style>