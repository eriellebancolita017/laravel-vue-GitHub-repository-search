<template>
  <div>
    <loading v-model:active="isLoading" :is-full-page="true" :height="128" :width="128" color='blue' />
    <div v-if="!isLoading">
      <repo-table :initial-data="repos" endpoint="/api/repos"></repo-table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import RepoTable from './components/RepoTable.vue';
import axios from 'axios';
import Loading from 'vue-loading-overlay'

export default {
  components: {
    RepoTable,
    Loading
  },
  setup() {
    const repos = ref(null);
    const isLoading = ref(true);

    onMounted(() => {
      axios.get('/api/repos')
        .then(response => {
          repos.value = response.data;
          isLoading.value = false;
        })
        .catch(error => {
          console.error(error);
          isLoading.value = false;
        });
    });

    return {
      repos,
      isLoading
    };
  },
};
</script>