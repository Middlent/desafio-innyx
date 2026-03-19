<script setup lang="ts">
import { ref } from 'vue'

const props = defineProps({
  token: {},
  produto: {}
})

const prod:any = props.produto
const tok:any = props.token

const emit = defineEmits(['added', 'deleted'])

const editing = ref(false)

const currentFile:any = ref(null)
const listCategoria:any = ref([])

const phNome = ref('Nome')
const phDescricao = ref('Descricao')
const phPreco = ref('Preco')
const phValidade = ref('Validade')
const phCategoria = ref('Categoria')

const btnEdit = ref('Editar')
const btnDelete = ref('Deletar')
const btnVoltar = ref('Voltar')
const btnRegistrar = ref('Registrar')

const txtNome = ref('')
const txtDescricao = ref('')
const txtPreco = ref('')
const txtValidade = ref('')
const txtCategoria = ref('')

const errorNome = ref('')
const errorDescricao = ref('')
const errorPreco = ref('')
const errorValidade = ref('')

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
      'Authorization': `Bearer ${tok.token}`,
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(response => response.json())
  .then(data => {
      console.log(data)
    if(data.errors){
      if(data.errors.nome){
        errorNome.value = 'Nome inválido'
      }else{errorNome.value = ''}
      if(data.errors.descricao){
        errorDescricao.value = 'Descricao inválido'
      }else{errorDescricao.value = ''}
      if(data.errors.preco){
        errorPreco.value = 'Preco inválido'
      }else{errorPreco.value = ''}
      if(data.errors.validade){
        errorValidade.value = 'Validade inválido'
      }else{errorValidade.value = ''}
    }else{
      errorNome.value = ''
      errorDescricao.value = ''
      errorPreco.value = ''
      errorValidade.value = ''
      emit('added')
    }
  })
  .catch(error => console.error('Error:', error)); 
}

function voltar(){
  if(editing){
    const formData = new FormData();
    formData.append('nome', txtNome.value)
    formData.append('descricao', txtDescricao.value)
    formData.append('preco', txtPreco.value)
    formData.append('validade', txtValidade.value)
    formData.append('imagem', prod.imagem)
    formData.append('categoria_id', txtCategoria.value)
    formData.append('token_name', 'vue')

    fetch(`//localhost:8000/api/produto/${prod.id}`, {
      method: 'PATCH',
      headers: {
        'Authorization': `Bearer ${tok.token}`,
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      console.log(data)
    if(data.errors){
      if(data.errors.nome){
        errorNome.value = 'Nome inválido'
      }else{errorNome.value = ''}
      if(data.errors.descricao){
        errorDescricao.value = 'Descricao inválido'
      }else{errorDescricao.value = ''}
      if(data.errors.preco){
        errorPreco.value = 'Preco inválido'
      }else{errorPreco.value = ''}
      if(data.errors.validade){
        errorValidade.value = 'Validade inválido'
      }else{errorValidade.value = ''}
    }else{
      errorNome.value = ''
      errorDescricao.value = ''
      errorPreco.value = ''
      errorValidade.value = ''
      emit('added')
    }
  })
    .catch(error => console.error('Error:', error)); 
  }
  else{
    emit('added')
  }
}




function edit_switch(){
  txtNome.value = prod.nome
  txtPreco.value = prod.preco
  txtValidade.value = prod.validade
  txtCategoria.value = prod.categoria_id
  txtDescricao.value = prod.descricao
  editing.value = !editing.value
  if(editing.value){
    btnEdit.value = "Cancelar"
    btnVoltar.value = "Salvar"
  }else{
    btnEdit.value = "Editar"
    btnVoltar.value = "Voltar"
  }
}

function retrieve_categorias(){
  if(tok.token){
    fetch('//localhost:8000/api/categoria', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${tok.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      listCategoria.value = data
      console.log(data)
    })
    .catch(error => console.error('Error:', error));
  }
}



retrieve_categorias();

</script>

<template>
  <div v-if="!prod">
    <div class="d-flex justify-content-center align-items-center bg-primary bg-gradient" style="height: 95vh;">
      <div class="d-flex flex-column p-4 bg-primary-subtle bg-gradient rounded-3 w-75">
        <h1 class="mb-4 mx-auto">Novo Produto</h1>
        <div class="form-floating mb-1">
          <input class="form-control" type="file" id="formFile" @change="handleFileChange">
        </div>
        <div class="text-danger">{{ errorNome }}</div>
        <div class="form-floating mb-1">
          <input class="form-control" id="floatingInput" placeholder={{phNome}} v-model='txtNome'>
          <label for="floatingInput">{{phNome}}</label>
        </div>
        <div class="text-danger">{{ errorDescricao }}</div>
        <div class="form-floating h-25 mb-1">
          <input class="form-control h-100 text-start" id="floatingInput" placeholder={{phDescricao}} v-model='txtDescricao'>
          <label for="floatingInput">{{phDescricao}}</label>
        </div>
        <div class="d-flex flex-row">
          
          <div class="form-floating">
            <input class="form-control h-100" id="floatingInput" placeholder={{phPreco}} v-model='txtPreco'>
            <div class="text-danger">{{ errorPreco }}</div>
            <label for="floatingInput">{{phPreco}}</label>
          </div>
          <div class="form-floating">
            <input type="date" class="form-control h-100" id="floatingInput" placeholder={{phValidade}} v-model='txtValidade'>
            <div class="text-danger">{{ errorValidade }}</div>
            <label for="floatingInput">{{phValidade}}</label>
          </div>
          <div class="d-flex flex-column w-25">
            <select class="w-100 h-100" v-model="txtCategoria">
              <option disabled value="">{{phCategoria}}</option>
              <option v-for='categoria in listCategoria' :value="categoria.id">
                {{categoria.nome}}
              </option>
            </select>
          </div>
        </div>
        <button class="p-2 mt-4 btn btn-primary" @click = 'registrar'>{{btnRegistrar}}</button>
      </div>
    </div>
  </div>
  <div v-else>
    <div class="d-flex justify-content-center align-items-center bg-primary bg-gradient" style="height: 95vh;">
      <div class="d-flex flex-column p-4 bg-primary-subtle bg-gradient rounded-3 w-50">
        <div class="d-flex flex-row mb-2" >
          <div class="w-50">
            <img class="img-fluid img-thumbnail rounded float-start h-100" :src="'http://localhost:8000'+prod.url">
          </div>
          <div class="w-50">
            <div class="d-flex flex-column">
              <div v-if="!editing">
                <h2 class="mb-0 ms-2">{{phNome}}</h2>
                <h3 class="mb-1 ms-4">{{prod.nome}}</h3>
              </div>
              <div v-else class="form-floating mb-2">
                <input class="form-control" id="floatingInput" placeholder={{phNome}} v-model='txtNome'>
                <div class="text-danger">{{ errorNome }}</div>
                <label for="floatingInput">{{phNome}}</label>
              </div>
              <div v-if="!editing">
                <h2 class="mb-0 ms-2">{{phPreco}}</h2>
                <h3 class="mb-1 ms-4">R$ {{prod.preco.toFixed(2)}}</h3>
              </div>
              <div v-else class="form-floating mb-2">
                <input class="form-control h-100" id="floatingInput" placeholder={{phPreco}} v-model='txtPreco'>
                <div class="text-danger">{{ errorPreco }}</div>
                <label for="floatingInput">{{phPreco}}</label>
              </div>
              <div v-if="!editing">
                <h2 class="mb-0 ms-2">{{phValidade}}</h2>
                <h3 class="mb-1 ms-4">{{prod.validade.split('-')[2]}}/{{prod.validade.split('-')[1]}}/{{prod.validade.split('-')[0]}}</h3>
              </div>
              <div v-else class="form-floating mb-2">
                <input type="date" class="form-control h-100" id="floatingInput" placeholder={{phValidade}} v-model='txtValidade'>
                <div class="text-danger">{{ errorValidade }}</div>
                <label for="floatingInput">{{phValidade}}</label>
              </div>
              <div v-if="!editing">
                <h2 class="mb-0 ms-2">{{phCategoria}}</h2>
                <h3 class="mb-1 ms-4">{{prod.categoria}}</h3>
              </div>
              <div v-else class="d-flex flex-column w-100 ">
                <select class="w-100 h-100" v-model="txtCategoria">
                  <option disabled value="">{{phCategoria}}</option>
                  <option v-for='categoria in listCategoria' :value="categoria.id">
                    {{categoria.nome}}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div v-if="!editing">
          <h2 class="mb-0 ">{{phDescricao}}</h2>
          <p class="mb-1 ms-2">{{prod.descricao}}</p>
        </div>
        <div v-else class="form-floating h-25 mb-1">
          <input class="form-control h-100 text-start" id="floatingInput" placeholder={{phDescricao}} v-model='txtDescricao'>
          <div class="text-danger">{{ errorDescricao }}</div>
          <label for="floatingInput">{{phDescricao}}</label>
        </div>
        <div v-if="tok.canManageProduto" class="d-flex flex-row">
          <button class="p-2 mt-4 mx-1 btn btn-primary w-50" @click="edit_switch">{{btnEdit}}</button>
          <button class="p-2 mt-4 mx-1 btn btn-danger w-50" @click="emit('deleted',produto)">{{btnDelete}}</button>
        </div>
        <button v-if="!prod" class="p-2 mt-4 btn btn-primary" @click = 'registrar'>{{btnRegistrar}}</button>
        <button v-else class="p-2 mt-4 btn btn-primary" @click = 'voltar'>{{btnVoltar}}</button>
        
      </div>
    </div>
  </div>
</template>
<style scoped></style>
