<template>
    <section class="bg--secondary space--sm ptb40">
        <div class="container">
            <form-wizard title="Create your Project"
                             color="#34C4F8"
                             @on-complete="createProject"
                             subtitle="">
                    <tab-content
                            :before-change="validateBasicInfo"
                            title="Basic information">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Project name</label>
                                    <div class="col-md-10">
                                        <input v-model="project.name"
                                               v-validate="'required|min:3|max:40'"
                                               id="name"
                                               min="3"
                                               max="40"
                                               class="form-control"
                                               name="name"
                                               type="text">
                                        <span>{{errors.first('name')}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="website" class="col-md-4 control-label">Project website</label>
                                    <div class="col-md-10">
                                        <input v-model="project.website"
                                               v-validate="'required|url'"
                                               id="website"
                                               class="form-control"
                                               name="website"
                                               type="text">
                                        <span>{{errors.first('website')}}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="launch-date" class="col-md-5 control-label">Project launch date</label>
                                    <div class="col-md-10">
                                        <date-picker format="yyyy-MM-d"
                                                     name="launch-date"
                                                     v-validate="'required'"
                                                     data-vv-as="launch date"
                                                     v-model="project.launch_date"
                                                     id="launch-date"
                                                     class="form-control"/>
                                        <span>{{errors.first('launch-date')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="short-description">Project short description</label>
                                    <textarea class="form-control"
                                              v-model="project.short_description"
                                              id="short-description"
                                              name="short-description"
                                              v-validate="'required|min:10|max:500'"
                                              rows="10"
                                              data-vv-as="description"
                                              placeholder="Provide short description of your project">
                            </textarea>
                                    <span>{{errors.first('short-description')}}</span>
                                </div>
                            </div>
                        </div>
                    </tab-content>
                    <tab-content :before-change="validateTokenInfo"
                                 title="Token description">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="symbol" class="col-md-4 control-label">Token symbol</label>
                                    <div class="col-md-10">
                                        <input v-model="project.symbol"
                                               v-validate="'required|min:2|max:7'"
                                               id="symbol"
                                               min="3"
                                               max="40"
                                               class="form-control"
                                               name="symbol"
                                               type="text">
                                        <span>{{errors.first('symbol')}}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="stage" class="col-md-4 control-label">ICO stage</label>
                                    <div class="col-md-10">
                                        <select v-model="project.stage" class="form-control" id="stage">
                                            <option value="pre-ico">Pre-ICO</option>
                                            <option value="ico">ICO</option>
                                        </select>
                                        <span>{{errors.first('stage')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total-supply" class="col-md-4 control-label">Total supply</label>
                                    <div class="col-md-10">
                                        <input v-model="project.total_supply_token"
                                               v-validate="'required|numeric|min_value:0'"
                                               data-vv-as="total supply"
                                               id="total-supply" min="0"
                                               class="form-control"
                                               name="total-supply"
                                               type="number">
                                        <span>{{errors.first('total-supply')}}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="initial-price" class="col-md-4 control-label">Token initial price</label>
                                    <div class="col-md-10">
                                        <input v-model="project.initial_price"
                                               v-validate="'required|decimal|min_value:0'"
                                               data-vv-as="initial price"
                                               id="initial-price" min="0"
                                               class="form-control"
                                               name="initial-price"
                                               type="number">
                                        <span>{{errors.first('initial-price')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="full-description">Project description</label>
                                <textarea v-model="project.full_description"
                                          class="form-control"
                                          id="full-description"
                                          name="full-description"
                                          v-validate="'required|min:10|max:100'"
                                          rows="10"
                                          data-vv-as="full description"
                                          placeholder="Provide full description of your project">
                            </textarea>
                                <span>{{errors.first('full-description')}}</span>
                            </div>
                        </div>
                    </tab-content>
                    <tab-content title="Assign team">
                        Yuhuuu! This seems pretty damn simple
                    </tab-content>
                </form-wizard>
        </div>
    </section>
</template>

<script>
    import {FormWizard, TabContent} from 'vue-form-wizard'
    import 'vue-form-wizard/dist/vue-form-wizard.min.css'
    import DatePicker from 'vuejs-datepicker'

    const resolveIfValid = (res, rej) => (isValid) => isValid ? res(isValid) : rej();

    export default {
        name: "create-project-view",
        components: {
            FormWizard,
            TabContent,
            DatePicker
        },
        data () {
            return {
                project: {
                    name: '',
                    symbol: '',
                    total_supply_token: 0,
                    stage: 'pre-ico',
                    launch_date: null,
                    initial_price: 0,
                    short_description: '',
                    full_description: ''
                }
            }
        },
        methods: {
            validateBasicInfo () {
                return new Promise((resolve, reject) => {
                    this.$validator.validateAll(['name', 'website', 'launch-date', 'short-description'])
                        .then(resolveIfValid(resolve, reject))
                })
            },
            validateTokenInfo () {
                return new Promise((resolve, reject) => {
                    this.$validator.validateAll(['symbol', 'initial-price', 'total-supply', 'full-description'])
                        .then(resolveIfValid(resolve, reject))
                })
            },
            createProject () {
                const data = Object.assign({}, this.project);
                data.launch_date = data.launch_date.toJSON();

                axios.post('/project/store', data)
                    .then(() => window.location.replace('/profile/projects'))
                    .catch(console.error)
            }
        }
    }
</script>

<style type="text/scss" lang="scss">
    #launch-date {
        width: 100%;
    }
</style>
