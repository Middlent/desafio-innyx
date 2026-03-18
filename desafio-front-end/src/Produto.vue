<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  token: String
})

const emit = defineEmits(['added'])

const phNome = ref('Nome')
const phDescricao = ref('Descricao')
const phPreco = ref('Preco')
const phValidade = ref('Validade')
const phImagem = ref('Imagem')
const phCategoria = ref('Categoria')

const btnRegistrar = ref('Registrar')

const txtNome = ref('')
const txtDescricao = ref('')
const txtPreco = ref('')
const txtValidade = ref('')
const txtImagem = ref('')
const txtCategoria = ref('')

function registrar(){
  const postData = {
    nome: txtNome.value,
    descricao: txtDescricao.value,
    preco: txtPreco.value,
    validade: txtValidade.value,
    imagem: txtImagem.value,
    categoria_id: txtCategoria.value,
    token_name: 'vue'
  };

  fetch('//localhost:8000/api/produto', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${props.token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify(postData)
  })
  .then(response => response.json())
  .then(data => {
    emit('added')
  })
  .catch(error => console.error('Error:', error));
  
}

</script>

<template>
  <input :placeholder='phNome' v-model='txtNome'/><br>
  <input :placeholder='phDescricao'  v-model='txtDescricao'/><br>
  <input :placeholder='phPreco'  v-model='txtPreco'/><br>
  <input :placeholder='phValidade' v-model='txtValidade'/><br>
  <input :placeholder='phImagem'  v-model='txtImagem'/><br>
  <input :placeholder='phCategoria'  v-model='txtCategoria'/><br>
  <button @click = 'registrar'>{{btnRegistrar}}</button><br>
  <br>
</template>
<style scoped></style>
