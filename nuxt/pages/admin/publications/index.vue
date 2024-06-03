<script lang="ts" setup>
import moment from "moment";
import { usePublicationStore } from "~/stores/publication";

const publications = usePublicationStore()
const router = useRouter()
const isLoading = ref(true)
const page = ref(1)
const pageCount = 5
const totalCount = ref(0)
const q = ref('')
const isOpen = ref(false)
const toast = useToast()

onMounted(() => {
  page.value = router.currentRoute.value.query.page || 1
  publications.getPublications({ page: page.value }, handleFinish)
})

const handleFinish = (status, total) => {
  totalCount.value = total || 0
  if(status) {}
  isLoading.value = false;
}

const columns = [{
  key: 'title',
  label: 'Title'
}, {
  key: 'pub_date',
  label: 'Date'
}, {
  key: 'description',
  label: 'Description'
}, {
  key: 'image',
  label: 'Image'
}, {
  key: 'platform',
  label: 'Platform'
}, {
  key: 'link',
  label: 'Link'
}, {
  key: 'metrics',
  label: 'Metrics'
}, {
  key: 'created_at',
  label: 'Create Date'
}, {
  key: "control",
  label: ""
}];

watch(page, (newValue, oldValue) => {
  publications.getPublications({ page: newValue, limit: pageCount }, handleFinish)
});

const handleEdit = (id) => {
  router.push({
    path: "/admin/publications/edit",
    query: {
      id: id,
      edit: true
    }
  })
}

const handleDelete = (id) => {
  publications.deletePublication(id, ({ data }) => {
    if(!data.ok) {
      toast.add({ title: 'Delete Failed!', description: data.messages, color: 'red' })
    } else {
      publications.getPublications({ page: page.value }, handleFinish)
      toast.add({ title: 'Delete Success!' })
    }
  })
}
</script>

<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'My Publications' }]"
    class="mb-5"
  />

  <UDivider class="mb-7" />

  <UCard :ui="{ body: { base: 'grid grid-cols-1' } }">
    <div class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700">
<!--      <UInput v-model="q" placeholder="Filter..." />-->
      <UButton
        class="ml-auto"
        icon="i-heroicons-plus"
        size="sm"
        color="primary"
        variant="solid"
        label="Add new publication"
        to="/admin/publications/create"
        :trailing="false"
      />
    </div>

    <UTable :rows="publications.list"
      :columns="columns"
      :loading="isLoading"
      :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
      :progress="{ color: 'primary', animation: 'carousel' }"
    >
      <template #created_at-data="{ row }">
        {{ moment(row.created_at).fromNow() }}
      </template>
      <template #link-data="{ row }">
        <ULink
          :to="row.link"
          target="_blank"
          active-class="text-primary"
          inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
        >
          {{ row.link }}
        </ULink>
      </template>
      <template #metrics-data="{ row }">
        <UIcon name="i-heroicons-eye" /> {{ row.views }} |
        <UIcon name="i-heroicons-hand-thumb-up" /> {{ row.views }} |
        <UIcon name="i-heroicons-chat-bubble-oval-left" /> {{ row.views }} |
        <UIcon name="i-heroicons-heart" /> {{ row.views }}
      </template>
      <template #control-data="{ row }">
        <UButton
          icon="i-heroicons-pencil-square"
          class="mr-1"
          size="sm"
          color="primary"
          square
          variant="solid"
          @click="(e) => handleEdit(row.id)"
        />
        <UButton
          icon="i-heroicons-trash"
          size="sm"
          color="red"
          square
          variant="solid"
          @click="(e) => handleDelete(row.id)"
        />
      </template>
    </UTable>

    <div class="flex justify-end px-3 py-3.5 border-t border-gray-200 dark:border-gray-700">
      <UPagination v-model="page" :page-count="pageCount" :total="totalCount" />
    </div>

    <UModal v-model="isOpen">
      <UCard :ui="{ ring: '', divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
        <template #header>
        </template>
        <template #footer>
        </template>
      </UCard>
    </UModal>
  </UCard>
</template>
