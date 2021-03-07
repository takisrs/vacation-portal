<template>
    <div role="alert" :class="'alert alert--'+className">
        {{ message }}
        <a href="#" class="alert__close" @click="$event.target.parentElement.style.display='none'">X</a>   
    </div>
</template>

<script>
export default {
    props: ['message', 'className']
}
</script>