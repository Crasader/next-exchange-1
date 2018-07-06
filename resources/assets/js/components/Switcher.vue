<template>
    <div class="switcher">
        <input v-model="checkerValue" :checked="checked" type="checkbox" id="checker" class="switcher"/>
        <label :style="{width: width}" for="checker"></label>
    </div>
</template>

<script>
  export default {
    name: "switcher",
    props: {
      checked: {
        type: Boolean,
        default: false
      },
      value: Boolean,
      width: String
    },
    data() {
      return {
        checkerValue: this.value
      }
    },
    watch: {
      checkerValue(value) {
        this.$emit('input', value);
      }
    }
  }
</script>

<style type="text/css" lang="scss">
    .switcher {
        input[type=checkbox] {
            display: none;
        }

        label {
            cursor: pointer;
            text-indent: -9999px;
            width: 100px;
            height: 30px;
            background: grey;
            display: block;
            border-radius: 100px;
            position: relative;
        }

        label:after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 21px;
            height: 21px;
            background: #fff;
            border-radius: 90px;
            transition: 0.3s;
        }

        input:checked + label {
            background: #1e9dff;
        }

        input:checked + label:after {
            left: calc(100% - 5px);
            transform: translateX(-100%);
        }

        label:active:after {
            width: 21px;
        }
    }

</style>