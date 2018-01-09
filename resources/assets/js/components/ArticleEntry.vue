<template>
    <li @click="activate" v-bind:class="{ active: isActive }"><slot></slot></li>
</template>

<script>
export default {
    props: {
        articleId: {
            type: String,
            required: true
        },
        url: {
            type: String
        }
    },
    data() {
        return {
            isActive: false
        }
    },
    methods: {
        activate() {
            this.isActive = true;
            var that = this;
            _.each(this.$parent.$children, function (el) {
                if (el == that) {
                    return;
                }
                if (el.setActive) {
                    el.setActive(false);
                }
            });
            window.display.clearContent();
            window.display.setText("Loading...");
            axios.get("/article/" + this.articleId)
            .then(function(resp) {
                window.display.setHeading(resp.data.headline)
                window.display.setText(resp.data.body);
                window.display.setFooter("<a href='" + resp.data.url + "'target='_blank'>Read more...</a>");
            });
        },
        setActive(state) {
            this.isActive = state;
        }
    }
}
</script>
