<template>
    <li class="one-comment-object" id="comment-{{ comment.id }}">
        <div class="one-comment">
            <img class="comment-avatar" src="/img/avatar.png" />
            <div class="comment-body">
                <div class="comment-author-name">{{ comment.author_name }}</div>
                <div class="comment-meta">{{ comment.updated_at }}</div>
                <div class="comment-content">{{ comment.content }}</div>
            </div>
        </div>
    </li>
</template>

<script type="text/babel">
    
    export default{
        name: 'comment',
        props: [ 'comment' ]
    }
</script>
