<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import Register from './Register.vue'
import Login from './Login.vue'
import Produtos from './Produtos.vue'

const token = ref('')
const routes:any = {
  '/': Produtos,
  '/login': Login,
  '/register': Register
}

token_check()

const currentPath = ref(window.location.hash)

window.addEventListener('hashchange', () => {
  currentPath.value = window.location.hash
})

const currentComponent = computed(() => {
  return routes[currentPath.value.slice(1) || '/']
})

watch(token, () => {
  token_check()
})

function token_check(){
  if(!token.value){
    window.location.hash = '#/login'
  }else{
    window.location.hash = '#'
  }
}
</script>

<template>
    <component @token='(token_txt: string) => token = token_txt' :is="currentComponent" :token="token"/>
</template>
<style scoped></style>
