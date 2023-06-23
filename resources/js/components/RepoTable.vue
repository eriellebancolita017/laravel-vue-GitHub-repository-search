<template>
  <loading v-model:active="isLoading" :is-full-page="true" :height="128" :width="128" color='blue' />
  <div class="py-10 px-4 sm:px-6 lg:px-8">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-base font-semibold leading-6 text-gray-900">Repositories</h1>
        <p class="mt-2 text-sm text-gray-700">A list of 500 php topic repositories of the GitHub</p>
        <div>
          <form @submit.prevent="searchByName">
            <div class="flex items-center border-b border-teal-500 py-2">
              <input v-model="repoName"
                class="appearance-none bg-transparent border-none w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none"
                type="text" placeholder="Search...">
              <button
                class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded"
                type="submit">
                Search
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">ID</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    <button v-on:click="sortRepo('name')" class="group inline-flex">
                      Name
                      <span
                        class="invisible ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <ChevronDownIcon v-if="sortDirection.name === 'asc'"
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                        <ChevronUpIcon v-else
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                      </span>
                    </button>
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Full Name</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">HTML URL</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Language</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                    <button v-on:click="sortRepo('updated_at')" class="group inline-flex">
                      Last Updated
                      <span
                        class="invisible ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <ChevronDownIcon v-if="sortDirection.updated_at === 'desc'"
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                        <ChevronUpIcon v-else
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                      </span>
                    </button>
                  </th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last Pushed</th>
                  <th scope="col" class="pl-3 pr-4 sm:pr-6 py-3.5 text-left text-sm font-semibold text-gray-900 sm:pr-6">
                    <button v-on:click="sortRepo('stargazers_count')" class="group inline-flex">
                      Stargazers Count
                      <span
                        class="invisible ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                        <ChevronDownIcon v-if="sortDirection.stargazers_count === 'desc'"
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                        <ChevronUpIcon v-else
                          class="invisible ml-2 h-5 w-5 flex-none rounded text-gray-400 group-hover:visible group-focus:visible"
                          aria-hidden="true" />
                      </span>
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="repo in repos" :key="repo.id">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ repo.id }}
                  </td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ repo.name }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ repo.full_name }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ repo.html_url }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ repo.language }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ formatDate(repo.updated_at) }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ formatDate(repo.pushed_at) }}</td>
                  <td class="whitespace-nowrap pl-8 pr-4 py-4 text-sm text-gray-600 sm:pr-6">{{ repo.stargazers_count }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between mt-3">
            <div>
              <p class="text-sm text-gray-700">
                Showing
                {{ ' ' }}
                <span class="font-medium">{{ start }}</span>
                {{ ' ' }}
                to
                {{ ' ' }}
                <span class="font-medium">{{ end }}</span>
                {{ ' ' }}
                of
                {{ ' ' }}
                <span class="font-medium">{{ total }}</span>
                {{ ' ' }}
                results
              </p>
            </div>
            <div>
              <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                <button :disabled="!havePrevious" v-on:click="goToPrevious()"
                  class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-25">
                  <span class="sr-only">Previous</span>
                  <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                </button>
                <!-- Current: "z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600", Default: "text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0" -->
                <button v-if="page > 3" v-on:click="goToPage(1)"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">1</button>
                <span v-if="page > 4"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">...</span>
                <button v-on:click="goToPage(page - 2)" v-if="page > 2"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{
                    page - 2 }}</button>
                <button v-on:click="goToPage(page - 1)" v-if="page > 1"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{
                    page - 1 }}</button>
                <button v-on:click="goToPage(page)"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
                    page }}</button>
                <button v-on:click="goToPage(page + 1)" v-if="haveNext"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{
                    page + 1 }}</button>
                <button v-on:click="goToPage(page + 2)" v-if="totalPages - page > 1"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{
                    page + 2 }}</button>
                <span v-if="totalPages - page > 3"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">...</span>
                <button v-on:click="goToPage(totalPages)" v-if="totalPages - page > 2"
                  class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">{{
                    totalPages }}</button>
                <button :disabled="!haveNext" v-on:click="goToNext()"
                  class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 disabled:opacity-25">
                  <span class="sr-only">Next</span>
                  <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
import axios from 'axios';
import { ref, computed, watchEffect } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon, ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/20/solid'
import Loading from 'vue-loading-overlay'

export default {
  props: ['initialData', 'endpoint'],
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    Loading
  },
  setup(props) {
    const repos = ref(props.initialData.data);
    const page = ref(Number(props.initialData.page));
    const perPage = ref(Number(props.initialData.per_page));
    const total = ref(Number(props.initialData.total));
    const repoName = ref('');
    const isLoading = ref(false);
    const sortDirection = ref({
      name: 'desc',
      stargazers_count: 'desc',
      updated_at: 'desc',
    });
    const sortedName = ref('')

    const totalPages = computed(() => {
      return Math.ceil(total.value / perPage.value);
    });

    const start = computed(() => {
      return (page.value - 1) * perPage.value + 1;
    });

    const end = computed(() => {
      return Math.min(page.value * perPage.value, total.value);
    });

    const havePrevious = computed(() => {
      return page.value > 1;
    });

    const haveNext = computed(() => {
      return page.value < totalPages.value;
    });

    const searchByName = async () => {
      isLoading.value = true;
      try {
        const response = await axios.get(props.endpoint, {
          params: {
            search: repoName.value,
          },
        });
        repos.value = response.data.data;
        page.value = Number(response.data.page);
        perPage.value = Number(response.data.per_page);
        total.value = Number(response.data.total);
      } catch (error) {
        console.error(error);
      } finally {
        isLoading.value = false;
      }
    };

    const sortRepo = async (sortName) => {
      sortedName.value = sortName
      isLoading.value = true;
      try {
        const response = await axios.get(props.endpoint, {
          params: {
            page: page.value,
            per_page: perPage.value,
            search: repoName.value,
            sort: sortName,
            sort_direction: sortDirection.value[sortName],
          },
        });
        repos.value = response.data.data;
        page.value = Number(response.data.page);
        perPage.value = Number(response.data.per_page);
        total.value = Number(response.data.total);
        sortDirection.value[sortName] =
          sortDirection.value[sortName] === 'asc' ? 'desc' : 'asc';
      } catch (error) {
        console.error(error);
      } finally {
        isLoading.value = false;
      }
    };

    const formatDate = (dateString) => {
      const date = new Date(dateString);
      return `${date.toLocaleDateString()} ${date.toLocaleTimeString()}`;
    };

    watchEffect(() => {
      sortDirection.value = {
        name: 'desc',
        stargazers_count: 'desc',
        updated_at: 'desc',
      };
    });

    return {
      repos,
      page,
      perPage,
      total,
      start,
      end,
      havePrevious,
      haveNext,
      repoName,
      isLoading,
      sortDirection,
      totalPages,
      sortedName,
      searchByName,
      sortRepo,
      formatDate
    };
  },
  methods: {
    async goToPrevious() {
      if (this.havePrevious) {
        this.page--;
        await this.fetchData();
      }
    },
    async goToNext() {
      if (this.haveNext) {
        this.page++;
        await this.fetchData();
      }
    },
    async goToPage(value) {
      if (value >= 1 && value <= this.totalPages) {
        this.page = value;
        await this.fetchData();
      }
    },
    async fetchData() {
      this.isLoading = true;
      try {
        const sortDirection = this.sortDirection[this.sortedName] || '';
        const response = await axios.get(this.endpoint, {
          params: {
            page: this.page,
            per_page: this.perPage,
            search: this.repoName,
            sort: this.sortedName,
            sort_direction: sortDirection
          },
        });
        this.repos = response.data.data;
        this.total = Number(response.data.total);
        this.perPage = Number(response.data.per_page);
      } catch (error) {
        console.error(error);
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>