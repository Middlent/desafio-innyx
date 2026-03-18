<script setup lang="ts">
import { computed, ref } from 'vue'
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
const selecionado:any = ref(null)

const teste = computed(() => {
  return adding
})

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

function openAdd(){
  selecionado.value = null
  adding.value = true
}

function openView(produto:any){
  selecionado.value = produto
  adding.value = true
}

function closeAdd(){
  adding.value = false
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

    <Produto v-if="teste.value" @added="() => {closeAdd(); attLista()}" :token="token" :produto="selecionado"/>
    
    <div v-else>
      <div class="d-flex justify-content-end mb-2">
        <button class="btn btn-light" @click="logout">{{ btnLogout }}</button>
      </div>
      <div class="bg-light bg-opacity-75 rounded-3">
        <div class="input-group p-1">
          <button class="btn btn-outline-secondary" type="button" @click="openAdd">{{ btnAdd }}</button>
          <input type="text" class="form-control" placeholder="Pesquisar">
          <button class="btn btn-outline-secondary" type="button">Buscar</button>
        </div>
      </div>
      
      
      <div class="p-2 bg-primary-subtle mt-1 rounded">
        <table class="table table-responsive table-primary table-hover" style="table-layout: fixed">
          <thead>
            <tr>
              <th>IMG</th>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Preço</th>
              <th>Validade</th>
              <th>Deletar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="produto in listProduto" @click="openView(produto)">
              <td><img class="img-fluid" :src="'http://localhost:8000'+produto.url"></td>
              <td>{{ produto.nome }}</td>
              <td class="text-truncate">{{ produto.descricao }}</td>
              <td>{{ produto.preco }}</td>
              <td>{{ produto.validade }}</td>
              <td class="position-relative">
                <button
                  @click="deletar(produto)" 
                  class="btn btn-danger w-auto h-auto stretched-link d-block">
                  {{btnDeletar}}
                </button>
              </td> 
            </tr>
          </tbody>
        </table>  
      </div>
    </div>
  </div>
</template>
<style scoped></style>
