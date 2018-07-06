<template>
    <div class="bg--secondary space--sm ptb40">
        <div class="container">
            <h3>Under construction</h3>
            <h1>The inbox</h1>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="boxed boxed--lg boxed--border bg-white">
                    <div class="conversations-list-block">
                        <ul>
                            <li v-for="conversation in conversations">
                                <div class="conversation-block"
                                     :class="{'active': conversation === selectedConversation}"
                                     @click="onSelectConversation(conversation)">
                                    <div class="author-avatar">
                                        <img :src="conversation.avatar_url" alt="">
                                    </div>
                                    <div class="conversation-info-block">
                                        <div class="conversation-header">
                                            <b>{{conversation.name}}</b>
                                            <i v-if="conversation.lastMessage">{{conversation.lastMessage.humans_date}}</i>
                                        </div>
                                        <div v-if="conversation.lastMessage" class="conversation-body">
                                            {{dotsOnOverFlow(conversation.lastMessage.message)}}
                                        </div>
                                        <div v-else>
                                            Sorry, this is empty conversation
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="boxed boxed--lg boxed--border bg-white">
                    <div v-show="selectedConversation" class="messages-block">
                        <ul>
                            <li :class="{'message-my': message.is_my, 'message-not-my': !message.is_my}"
                                v-for="message in messages">
                                <div class="message-block">
                                    <div class="message-text">
                                        {{message.message}}
                                    </div>
                                    <div class="message-time">
                                        {{message.humans_date}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-show="selectedConversation" class="compose-message-block">
                        <input v-model.trim="messagePrototype" type="text" placeholder="Your message here..." />
                        <button @click="onSendMessageClick" class="next-button">
                            <i class="fa fa-send"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import {fetchData} from "../../helpers";

  const setVar = (context, key) => (val) => context[key] = val;

  export default {
    name: "inbox-view",
    data() {
      return {
        conversations: [],
        messages: [],
        selectedConversation: null,
        messagePrototype: ''
      }
    },
    created() {
      axios.get('/api/user/conversations')
        .then(fetchData)
        .then(setVar(this, 'conversations'));

      this.listen();
    },
    methods: {
      dotsOnOverFlow (text) {
        return text.length > 30 ? text.substring(0, 30) + '...' : text;
      },
      onSelectConversation (conversation) {
        this.messages = [];
        this.selectedConversation = conversation;
        this.getConversationMessages(conversation);
      },
      getConversationMessages (conversation, page = 1, limit = 30, append = false) {
        axios.get(`/api/user/${conversation.id}/messages?limit=${limit}&page=${page}`)
          .then(fetchData)
          .then(collection => {
            this.messages = append ? this.messages.merge(collection) : collection;
          });
      },
      onSendMessageClick () {
        if (this.messagePrototype.length <= 1) return;

        axios.post(`/api/user/${this.selectedConversation.id}/messages`, {message: this.messagePrototype})
          .then(fetchData)
          .then(message => {
            this.messages.push(message);
            this.messagePrototype = '';
          });
      },
      listen () {
        window.Echo.private('App.Models.User.' + this.$authenticatedUser.id)
          .notification((notification) => {
            this.messages.push(notification.data);
          });
      }
    },
    computed: {
      reversedMessages() {
        return this.messages.reverse()
      }
    }
  }
</script>

<style type="text/scss" lang="scss">
    .conversation-block {
        display: flex;
        flex-direction: row;
        padding: 10px;
        margin: 5px;
        box-shadow: 0px 1px 2px 0px #7a7a7f;
        transition: all .03s ease;

        &:hover {
            cursor: pointer;
            background-color: #f5f9fb
        }

        .author-avatar img {
            margin-right: 10px;
            height: 50px;
            border-radius: 50%;
        }

        .conversation-info-block {
            display: flex;
            flex-direction: column;
        }
    }

    .conversation-block.active {
        background-color: #f5f9fb
    }

    .message-block {
        display: flex;
        padding: 5px;
        margin: 5px;
        width: 30%;
        box-shadow: 0px 1px 2px 0px #7a7a7f;
        justify-content: space-between;
    }

    .message-my {
        display: flex;
        justify-content: flex-end;
        .message-block {
            background-color: #0b0e0f;
            color: #fff1f1;
        }
    }

    .message-not-my {
        display: flex;
        justify-content: flex-start;
    }

    .messages-block {
        height: 500px;
        overflow-y: scroll;
    }

    .next-button {
        width: 52px;
        height: 26px;
        box-shadow: 0 15px 30px rgba(0, 198, 255, 0.3);
        border-radius: 7px;
        background-color: #00c6ff;
        color: #ffffff;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.28px;
        line-height: 26px;
        position: relative;
    }

    .compose-message-block {
        width: 100%;
        input {
            width: 90%;
        }
    }

</style>