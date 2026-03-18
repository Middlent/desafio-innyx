<script setup lang="ts">
import { ref } from 'vue'
import Produto from './Produto.vue'

const props = defineProps({
  token: String
})

const btnLogout = ref('Sair')
const btnAdd = ref('+')
const btnEditar = ref('Editar')
const btnDeletar = ref('Deletar')

const listProduto:any = ref([])
const adding = ref(false)

attLista()

function logout(){
  fetch(`//localhost:8000/api/logout`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${props.token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })
  .then(data => {
    console.log(data)
    window.location.hash = "#/login"
  })
  .catch(error => console.error('Error:', error));
}

function add(){
  adding.value = true
}

function editar(produto:any){

}

function deletar(produto:any){
  fetch(`//localhost:8000/api/produto/${produto.id}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${props.token}`,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  })
  .then(data => {
    console.log(data)
    attLista()
  })
  .catch(error => console.error('Error:', error));

}

function attLista(){
  if(props.token){
    fetch('//localhost:8000/api/produto', {
      method: 'GET',
      headers: {
        'Authorization': `Bearer ${props.token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      listProduto.value = data.data
      console.log(data.data)
    })
    .catch(error => console.error('Error:', error));
  }
}
</script>

<template>
  <div class="d-flex flex-column p-4 bg-primary bg-gradient"  style="height: 100vh;">
    <Produto v-if="adding" @added="attLista()" :token="token"/>
    <div class="d-flex justify-content-end">
      <button @click="logout">{{ btnLogout }}</button>
    </div>
    <div class="flex-row bg-light bg-opacity-75">
      <button @click="add">{{ btnAdd }}</button> 
      <div class="form-floating mb-1">
        <input type="email" class="form-control" id="floatingInput" placeholder="Pesquisar">
        <label for="floatingInput">Pesquisar</label>
      </div>
    </div>
    <div v-for="produto in listProduto">
      Id: {{produto.id}} | Nome: {{ produto.nome }} 
      <button @click="editar(produto)">{{ btnEditar }}</button> 
      <button @click="deletar(produto)">{{btnDeletar}}</button>
    </div>  
  </div>
</template>
<style scoped></style>
