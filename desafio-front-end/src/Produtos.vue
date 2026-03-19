<script setup lang="ts">
import { computed, ref } from 'vue'
import Produto from './Produto.vue'

const props = defineProps({
  token: {}
})

const tok:any = props.token

const phTitle = ref('Produtos')

const btnSwap = ref('Usuários')
const btnLogout = ref('Sair')
const btnAdd = ref('+')
const btnDeletar = ref('Deletar')

const managingUser = ref(false)

const listProduto:any = ref([])
const listUser:any = ref([])
const adding = ref(false)
const selecionado:any = ref(null)

const loading = ref(false)

const max_page = ref(1)
const current_page = ref(1)

const txtSearch = ref('')
const prompt = ref('')
const searching = ref(false)

const teste = computed(() => {
  return adding
})

attLista()

function logout(){
  fetch(`//localhost:8000/api/logout`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${tok.token}`,
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

function swap(){
  managingUser.value = !managingUser.value
  if(managingUser.value){
    phTitle.value = "Usuários"
    btnSwap.value = "Produtos"
  }else{
    phTitle.value = "Produtos"
    btnSwap.value = "Usuários"
  }
  attLista()
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

function alterarAdm(user:any, can:Number){
    loading.value = true
    const formData = new FormData();
    formData.append('canManageProduto', String(can))
    formData.append('canManageUser', user.canManageUser)

    fetch(`//localhost:8000/api/user/${user.id}`, {
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
      attLista()
    })
    .catch(error => console.error('Error:', error)); 
}

function alterarSuper(user:any, can:Number){
    loading.value = true
    const formData = new FormData();
    formData.append('canManageUser', String(can))
    formData.append('canManageProduto', user.canManageUser)

    fetch(`//localhost:8000/api/user/${user.id}`, {
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
      attLista()
    })
    .catch(error => console.error('Error:', error)); 
  
}

function deletar(produto:any){
  fetch(`//localhost:8000/api/produto/${produto.id}`, {
    method: 'DELETE',
    headers: {
      'Authorization': `Bearer ${tok.token}`,
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

function search(){
  if(txtSearch.value == ''){
    searching.value = false
  }else{
    searching.value = true
    prompt.value = txtSearch.value
    current_page.value = 1
  }
  attLista()
}

function attLista(){
  loading.value = true
  if(managingUser.value){
    if(tok.token && tok.canManageUser){
      let fetchUrl = `//localhost:8000/api/user`
      if(searching.value){
        fetchUrl += `/search/${prompt.value}`
      }
      fetchUrl += `?page=${current_page.value}`
      fetch(fetchUrl, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${tok.token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        listUser.value = data.data
        max_page.value = data.last_page
        console.log(data)
        loading.value = false
      })
      .catch(error => console.error('Error:', error));
    }
  }else{
    if(tok.token){
      let fetchUrl = `//localhost:8000/api/produto`
      if(searching.value){
        fetchUrl += `/search/${prompt.value}`
      }
      fetchUrl += `?page=${current_page.value}`
      fetch(fetchUrl, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${tok.token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        listProduto.value = data.data
        max_page.value = data.last_page
        console.log(data)
        loading.value = false
      })
      .catch(error => console.error('Error:', error));
    }
  }
}
</script>

<template>
  <div class="d-flex flex-column p-4 bg-primary bg-gradient" style="height: 100vh; overflow-y: auto;">

    <Produto v-if="teste.value" @added="() => {closeAdd(); attLista()}" @deleted="(produto:any) => {deletar(produto); closeAdd(); attLista();}" :token="tok" :produto="selecionado"/>
    
    <div v-else>
      <div class="d-flex flex-row">
        <h1 class="w-50 ms-2 text-white">
          {{ phTitle }}
        </h1>
        <div class="d-flex justify-content-end mb-2 w-50">
          <button v-if="tok.canManageUser" class="btn btn-light" @click="swap">{{ btnSwap }}</button>
          <button class="btn btn-light ms-4" @click="logout">{{ btnLogout }}</button>
        </div>
      </div>
      <div class="bg-light bg-opacity-75 rounded-3">
        <div class="input-group p-1">
          <button v-if="tok.canManageProduto && !managingUser" class="btn btn-outline-secondary" type="button" @click="openAdd">{{ btnAdd }}</button>
          <input type="text" class="form-control" placeholder="Pesquisar" v-model='txtSearch'>
          <button class="btn btn-outline-secondary" type="button" @click="search">Buscar</button>
        </div>
      </div>
      <div class="p-2 bg-primary-subtle mt-1 rounded table-responsive">
        <div v-if="searching">Buscando por "{{ prompt }}" <button class="btn btn-danger opacity-75" @click="txtSearch='';searching=false;attLista()">Cancelar</button></div>
        <div v-if="loading" class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <table v-else-if="!managingUser" class="table table-primary table-hover" style="table-layout: fixed">
          <thead>
            <tr>
              <th>IMG</th>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Preço</th>
              <th>Validade</th>
              <th v-if="tok.canManageProduto">Deletar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="produto in listProduto" @click="openView(produto)">
              <td><img class="img-fluid" :src="'http://localhost:8000'+produto.url"></td>
              <td>{{ produto.nome }}</td>
              <td class="text-truncate">{{ produto.descricao }}</td>
              <td>R$ {{ produto.preco.toFixed(2) }}</td>
              <td>{{produto.validade.split('-')[2]}}/{{produto.validade.split('-')[1]}}/{{produto.validade.split('-')[0]}}</td>
              <td v-if="tok.canManageProduto" class="position-relative">
                <button
                  @click.stop="deletar(produto)" 
                  class="btn btn-danger w-auto h-auto stretched-link d-block">
                  {{btnDeletar}}
                </button>
              </td> 
            </tr>
          </tbody>
        </table>  
        <table v-else class="table table-primary table-hover" style="table-layout: fixed">
          <thead>
            <tr>
              <th>Nome</th>
              <th>Email</th>
              <th>Admin</th>
              <th>Super Admin</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in listUser">
              <td>{{ user.name }}</td>
              <td class="text-truncate">{{ user.email }}</td>
              <td v-if="tok.canManageUser" class="position-relative">
                <button v-if="!user.canManageProduto"
                  @click.stop="alterarAdm(user,1)"
                  class="btn btn-danger w-auto h-auto stretched-link d-block">
                  Tornar Admin
                </button>
                <button v-else
                  @click.stop="alterarAdm(user,0)"
                  class="btn btn-success w-auto h-auto stretched-link d-block">
                  Retirar Admin
                </button>
              </td> 
              <td v-if="tok.canManageUser" class="position-relative">
                <button v-if="!user.canManageUser"
                  @click.stop="alterarSuper(user,1)"
                  class="btn btn-danger w-auto h-auto stretched-link d-block">
                  Tornar Super Admin
                </button>
                <button v-else
                  @click.stop="alterarSuper(user,0)"
                  class="btn btn-success w-auto h-auto stretched-link d-block">
                  Retirar Super Admin
                </button>
              </td> 
            </tr>
          </tbody>
        </table>  
      </div>

      <div class="p-2 bg-primary-subtle mt-1 rounded">
        <div class="mb-2 ms-1">
          Página {{ current_page }}
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li v-if='current_page > 1' class="page-item"><a class="page-link" href="#" @click="current_page=current_page-1; attLista()">Anterior</a></li>
                <li v-for="i in max_page" class="page-item"><a class="page-link" href="#" @click="current_page=i; attLista()">{{i}}</a></li>
                <li v-if="current_page < max_page" class="page-item" ><a class="page-link" href="#" @click="current_page=current_page+1; attLista()">Proximo</a></li>
            </ul>
        </nav>
      </div>
    </div>
  </div>
</template>
<style scoped></style>
