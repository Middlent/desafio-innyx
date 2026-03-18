<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  token: String,
  produto: {}
})

const emit = defineEmits(['added'])

const editing = ref(false)

const currentFile:any = ref(null)

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

function handleFileChange(event:any){
  const file = event.target.files[0];
  if (!file) return;

  currentFile.value = file;
}

function registrar(){
  const formData = new FormData();
  formData.append('nome', txtNome.value)
  formData.append('descricao', txtDescricao.value)
  formData.append('preco', txtPreco.value)
  formData.append('validade', txtValidade.value)
  formData.append('imagem', currentFile.value)
  formData.append('categoria_id', txtCategoria.value)
  formData.append('token_name', 'vue')

  fetch('//localhost:8000/api/produto', {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${props.token}`,
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data)
    emit('added')
  })
  .catch(error => console.error('Error:', error));
  
}

</script>

<template>
  <div v-if="!produto">
  <div v-if="currentFile">
    {{ currentFile.name }}
    {{ currentFile.type }}
  </div>
    
    <div class="d-flex justify-content-center align-items-center bg-primary bg-gradient" style="height: 95vh;">
      <div class="d-flex flex-column p-4 bg-primary-subtle bg-gradient rounded-3 w-75 h-75">
        <h1 class="mb-4 mx-auto">Novo Produto</h1>
        <div class="form-floating mb-1">
          <input class="form-control" type="file" id="formFile" @change="handleFileChange">
        </div>
        <div class="form-floating mb-1">
          <input class="form-control" id="floatingInput" placeholder={{phNome}} v-model='txtNome'>
          <label for="floatingInput">{{phNome}}</label>
        </div>
        <div class="form-floating h-25 mb-1">
          <input class="form-control h-100 text-start" id="floatingInput" placeholder={{phDescricao}} v-model='txtDescricao'>
          <label for="floatingInput">{{phDescricao}}</label>
        </div>
        <div class="d-flex flex-row">
          <div class="form-floating">
            <input class="form-control h-100" id="floatingInput" placeholder={{phPreco}} v-model='txtPreco'>
            <label for="floatingInput">{{phPreco}}</label>
          </div>
          <div class="form-floating">
            <input class="form-control h-100" id="floatingInput" placeholder={{phValidade}} v-model='txtValidade'>
            <label for="floatingInput">{{phValidade}}</label>
          </div>
          <div class="form-floating">
            <input class="form-control h-100" id="floatingInput" placeholder={{phCategoria}} v-model='txtCategoria'>
            <label for="floatingInput">{{phCategoria}}</label>
          </div>
        </div>
        <button class="p-2 mt-4 btn btn-primary" @click = 'registrar'>{{btnRegistrar}}</button>
      </div>
    </div>
  </div>
</template>
<style scoped></style>
