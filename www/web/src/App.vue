<script setup lang="ts">
import { onBeforeMount } from 'vue'
import HelloWorld from './components/HelloWorld.vue'
import TheWelcome from './components/TheWelcome.vue'
import http from './lib/http'

const ApiUrl = import.meta.env.VITE_API_DEVELOPMENT_URL;

onBeforeMount(async function(){
  let datas = await http.post({
    url: ApiUrl,
    data: {asdb:'asdadasf', masm:'dabsdask'},
    header: {Authorization: "bearer asdfghjk"}
  });
  console.log(datas);
});

</script>

<template>
  <header>
    <img alt="Vue logo" class="logo" src="./assets/logo.svg" width="125" height="125" />

    <div class="wrapper">
      <HelloWorld msg="You did it!" />
    </div>
  </header>

  <main>
    <TheWelcome />
  </main>
</template>

<style scoped>
header {
  line-height: 1.5;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }
}
</style>
