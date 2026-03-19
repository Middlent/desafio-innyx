<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import Register from './Register.vue'
import Login from './Login.vue'
import Produtos from './Produtos.vue'

const token:any = ref(null)
const routes:any = {
  '/': Produtos,
  '/login': Login,
  '/register': Register
}

setup()
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

function setup(){
  fetch('//localhost:8000/api/setup', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body:'{}',
  })
  .then(response => response.json())
  .catch(error => console.error('Error:', error));
}
</script>

<template>
    <component  @token='(token_obj: any) => token = token_obj' :is="currentComponent" :token="token"/>
</template>
<style scoped>
</style>
