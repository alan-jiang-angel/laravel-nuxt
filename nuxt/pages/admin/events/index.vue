<script lang="ts" setup>
import moment from "moment";
import { useEventStore } from "~/stores/event";

const events = useEventStore()
const router = useRouter()
const isLoading = ref(true)
const page = ref(1)
const pageCount = 5
const totalCount = ref(0)
const q = ref('')
const selected = ref([])
const toast = useToast()
const columns = [{
  key: 'title',
  label: 'Title'
}, {
  key: 'description',
  label: 'Description'
}, {
  key: 'date_time',
  label: 'Date and time'
}, {
  key: 'location',
  label: 'Location'
}, {
  key: 'created_at',
  label: 'Create Date'
}, {
  key: "control",
  label: ""
}];

onMounted(() => {
  page.value = router.currentRoute.value.query.page || 1
  events.getEvents({ page: page.value, limit: pageCount }, handleFinish)
})

const handleFinish = (status, total) => {
  totalCount.value = total || 0
  if(status) {}
  isLoading.value = false;
}

watch(page, (newValue, oldValue) => {
  events.getEvents({ page: newValue, limit: pageCount }, handleFinish)
});

const handleEdit = (id) => {
  router.push({
    path: "/admin/events/edit",
    query: {
      id: id,
      edit: true
    }
  })
}

const rows = computed(() => {
  return events.list.slice((page.value - 1) * pageCount, (page.value) * pageCount)
})

const handleDelete = (id) => {
  // let items = selected.value.map(item => item.id)
  events.deleteEvent(id, (status, result) => {
    if(!status) {
      console.log(result)
    } else {
      events.getEvents({ page: page.value }, handleFinish)
      toast.add({ title: 'Delete Success!' })
    }
  })
}

</script>

<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'Events' }]"
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
        label="Add new event"
        to="/admin/events/create"
        :trailing="false"
      />
    </div>

    <UTable :rows="events.list"
      :columns="columns"
      :loading="isLoading"
      :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
      :progress="{ color: 'primary', animation: 'carousel' }"
    >
      <template #created_at-data="{ row }">
        {{ moment(row.created_at).fromNow() }}
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
  </UCard>
</template>
