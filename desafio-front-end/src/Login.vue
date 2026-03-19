<script setup lang="ts">
import { ref } from 'vue'

const emit = defineEmits(['token'])

const phEmail = ref('E-mail')
const phSenha = ref('Senha')

const btnRegistrar = ref('Não tem conta?')
const btnLogar = ref('Entrar')

const txtEmail = ref('')
const txtSenha = ref('')

const teste = ref('')

function logar(){
  const postData = {
    email: txtEmail.value,
    password: txtSenha.value,
    token_name: 'vue'
  };

  fetch('//localhost:8000/api/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(postData)
  })
  .then(response => response.json())
  .then(data => {
    emit('token',data.plainTextToken)
  })
  .catch(error => console.error('Error:', error));
}

</script>

<template>
  <div class="d-flex justify-content-center align-items-center bg-primary bg-gradient" style="height: 100vh; overflow-y: auto;">
    <div class="d-flex flex-column p-4 bg-primary-subtle bg-gradient rounded-3" >
      <h1 class="my-4 mx-auto">Login</h1>
      <div class="form-floating mb-1">
        <input type="email" class="form-control" id="floatingInput" placeholder={{phEmail}} v-model='txtEmail'>
        <label for="floatingInput">{{phEmail}}</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder={{phSenha}} v-model='txtSenha'>
        <label for="floatingPassword">{{phSenha}}</label>
      </div>
      <button class="p-2 mt-4 btn btn-primary" @click = 'logar'>{{btnLogar}}</button>
      <a class="p-1 btn btn-link" href="#/register">{{btnRegistrar}}</a>
    </div>
  </div>
</template>
<style scoped></style>
