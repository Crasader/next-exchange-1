<template>
    <form class="pure-form chat-input" @submit.prevent="postMessage">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Write a message..." maxlength="255" v-model="text">
            <div class="input-group-append">
                <button class="btn btn-primary">Send</button>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                text: ''
            }
        },
        methods: {
            postMessage() {
                this.$emit('post', {
                    text: this.text
                });
                this.text = '';
            }
        }
    }
</script>

<style scoped>
    .chat-input {
        display: flex;
        background: #FFF;
    }
</style>