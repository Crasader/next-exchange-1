<template>
    <div class="boxed boxed--border bg-white">
        <div class="add-article-block">
            <div class="options">
                <div v-for="tab in tabs"
                     @click="selectTab(tab)"
                     :class="{'active': tab === activeTab}"
                     class="option">
                    <i :class="tab.icon"></i>
                    {{tab.text}}
                </div>
            </div>

                <div class="write-article" v-show="activeTab.type === 'simple'">
                    <div>
                        <quill-editor
                                v-validate:simpleArticleStrippedText="'max:200'"
                                data-vv-as="article"
                                name="simple-article"
                                :options="editorOptions"
                                v-model="article.simpleText" />
                        <span>{{errors.first('simple-article')}}</span>
                    </div>
                    <button @click="createArticle" class="article-button">Publish</button>
                </div>
                <div class="write-article" v-show="activeTab.type === 'photo'">
                    <div class="drage-box">
                        <div v-show="!article.photo" class="file-upload">
                            <div class="error-block">
                            </div>
                            <input class="file-upload-input2"
                                   v-validate.reject="'image|size:2048'"
                                   @change="onImageLoad"
                                   name="article-photo"
                                   accept=".jpg,.png,.bmp,.svg"
                                   type="file">
                            <div class="drag-text">
                                <img src="/img/uploadfile.svg" width="150" alt="" class="drag-img-icon justify-content-center" id="id_proof">
                                <h3>Share awesome photos with friends <br>Drop file or select from your device</h3>
                            </div>
                        </div>
                        <div>
                            <span>{{errors.first('article-photo')}}</span>
                        </div>
                        <div v-show="article.photo" class="photo-mask">
                            <img :src="article.photo" alt="">
                            <button @click="onArticlePhotoRemove" class="remove-btn"><i class="fa fa-times"></i></button>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <input v-model="article.photoText"
                                           v-validate="'max:150'"
                                           placeholder="Write something good"
                                           name="photo-text"
                                           type="text">
                                </div>
                            </div>
                            <button @click="createArticle" class="article-button">Publish</button>
                        </div>
                    </div>
                </div>
                <div class="write-article" v-show="activeTab.type === 'article'">
                    <div>
                        <quill-editor
                                v-validate:articleStrippedText="'max:1000'"
                                name="article-text"
                                :options="editorOptions"
                                data-vv-as="article"
                                v-model="article.articleText" />
                        <span>{{errors.first('article-text')}}</span>
                    </div>
                    <div v-show="article.photo" class="photo-mask">
                        <img :src="article.photo" alt="">
                        <button @click="onArticlePhotoRemove" class="remove-btn"><i class="fa fa-times"></i></button>
                    </div>
                    <label for="article-photo" class="article-button">
                        <i class="fa fa-camera"></i>
                    </label>
                    <input @change="onImageLoad" type="file" id="article-photo" style="display: none;"/>
                    <button @click="createArticle" class="article-button">Publish</button>
                </div>
        </div>
    </div>
</template>

<script>
  import { quillEditor } from 'vue-quill-editor'

  const stripString = (string) => string.replace(/(<([^>]+)>)/ig, '');

  export default {
    name: "create-article-view",
    components: {
      quillEditor
    },
    data () {
      return {
        tabs: [
          {text: 'Share an update', icon: 'fa fa-pencil', type: 'simple'},
          {text: 'Upload a photo', icon: 'fa fa-image', type: 'photo'},
          {text: 'Write an article', icon: 'fa fa-book', type: 'article'}
        ],
        activeTab: null,
        article: {
          photo: null,
          file: null,
          simpleText: '',
          photoText: '',
          articleText: ''
        },
        editorOptions: {
          modules: {
            toolbar: [
              ['bold', 'italic', 'underline'],
              [{
                'list': 'ordered'
              }, {
                'list': 'bullet'
              }],
              [{
                'indent': '-1'
              }, {
                'indent': '+1'
              }],
              [{
                'align': [false]
              }, {
                'align': 'center'
              }, {
                'align': 'right'
              }],

              ['clean']
            ]
          }
        }
      }
    },
    created () {
      this.activeTab = this.tabs[0]
    },
    methods: {
      selectTab (tab) {
        this.activeTab = tab;
      },
      onImageLoad (e) {
        if (!e.target.files && !e.target.files[0]) return;
        const reader = new FileReader();
        reader.onload = (e) => {
          this.article.photo = e.target.result;
        };
        this.article.file = e.target.files[0];
        reader.readAsDataURL(e.target.files[0]);
      },
      onArticlePhotoRemove () {
        this.article.photo = null;
        this.article.file = null;
      },
      createArticle () {
        const text = this.article[`${this.activeTab.type}Text`];
        const data = new FormData();
        data.append('text', text);
        data.append('type', this.activeTab.type);
        if (this.article.file) {
          data.append('image', this.article.file);
        }

        axios.post('/articles/create', data)
          .then(() => location.reload())
          .catch(console.error);
      }
    },
    computed: {
      articleStrippedText () {
        return stripString(this.article.articleText)
      },
      simpleArticleStrippedText () {
        return stripString(this.article.simpleText)
      }
    }
  }
</script>

<style type="text/scss" lang="scss">
    @import "~quill/dist/quill.bubble.css";
    @import "~quill/dist/quill.core.css";
    @import "~quill/dist/quill.snow.css";

    .write-article textarea {
        width: 100%;
    }

    .photo-mask {
        position: relative;
        width: 100%;
        text-align: center;
        img {
            max-width: 100%;
        }

        .form-group {
            margin-top: 10px;
        }

        .remove-btn {
            background: rgba(0, 0, 0, 0.31);
            padding: 10px;
            padding-top: 1.5px;
            padding-bottom: 1.5px;
            position: absolute;
            top: 0;
            right:0;
            z-index: 10;
            color: white;
        }
    }

    .article-button {
        background-color: #0073b1;
        border: 0;
        border-radius: 2px;
        color: #fff;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        font-family: inherit;
        height: 24px;
        line-height: 24px;
        overflow: hidden;
        outline-width: 2px;
        padding: 0 16px;
        position: relative;
        text-align: center;
        text-decoration: none;
        transition-duration: 167ms;
        vertical-align: middle;
        z-index: 0;
    }
</style>