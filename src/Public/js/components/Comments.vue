<template>
	<ul class="comments-list">
		<comment v-for="comment in comments" :comment="comment"></comment>
	</ul>
</template>

<script type="text/babel">
	import Comment from './Comment.vue';
	
    export default {
	    data () {
		    return {
			    comments: [],
		    }
	    },
	    components: { Comment },
	    props: {
		    news_id: Number,
	    },
	    methods: {
	    	log() {
	    		console.log('Clique ICI !!')
		    },
		    fetchComments: function () {
			    this.$http.get('/news/' + this.news_id + '/comments').then((response) => {
				    this.$set('comments', response.body);
			    }, (response) => {
				    console.log('NO NO NO')
			    });
		    }
	    },
        ready() {
	        this.fetchComments()
	        console.log('redfsfgasady')
        }
    }
</script>
