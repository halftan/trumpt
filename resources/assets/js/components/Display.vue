<template>
<div class="panel panel-info">
    <div class="panel-heading">
        {{ heading }}
    </div>
    <div class="panel-body" v-html="body"></div>
    <div class="panel-footer" v-html="footer"></div>
</div>
</template>

<script>
export default {
    data() {
        return {
            body: '',
            heading: '',
            footer: '',
        }
    },
    methods: {
        setHeading(text) {
            this.heading = text;
        },
        setFooter(html) {
            this.footer = html;
        },
        setBody(body) {
            this.body = body;
        },
        setText(text) {
            this.body = "<p>" + text + "</p>";
        },
        clearContent() {
            this.heading = this.body = this.footer = '';
        },
    },
    mounted() {
        window.display = this;
    }
}
</script>
