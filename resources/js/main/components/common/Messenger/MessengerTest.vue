<template>
    <div >
        <input type="submit" @click="$emit('test')">

        <slot></slot>
    </div>
</template>

<script>
    export default {
        created(){
            console.log(1);
        }
    }
</script>
