<template>
    <div class="chat-log" v-chat-scroll="{always: false}" @scroll-top="scrollTop()">
        <chat-message
                v-for="message in messages"
                :message="message"
                :key="message.id"></chat-message>
        <div class="empty" v-if="messages.length === 0">No messages in this chat...</div>
    </div>
</template>

<script>
    import ChatMessage from './ChatMessage.vue';

    export default {
        props: [
            'messages'
        ],
        components: {
            ChatMessage,
        },
        methods: {
            scrollTop() {
                this.$emit('scroll-top');
            }
        }
    }
</script>

<style lang="css">
    .chat-log {
        padding: 15px;
        overflow-y: scroll;
        max-height: 775px;
    }

    .chat-log::-webkit-scrollbar {
        display: none;
    }

    .empty {
        padding: 1rem;
        text-align: center;
    }
</style>