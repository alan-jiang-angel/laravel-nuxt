<script lang="ts" setup>
import moment from "moment";
import { useMessageStore } from "~/stores/message";
import { useSearch } from "~/composables/useSearch";

const router = useRouter()
const messages = useMessageStore()
const isLoading = ref(true)
const page = ref(1)
const pageCount = 5
const totalCount = ref(0)
const q = ref('')
const selected = ref([])
const toast = useToast()
const columns = [{
  key: 'email',
  label: 'Email'
}, {
  key: 'description',
  label: 'Description'
}, {
  key: 'created_at',
  label: 'Created Date'
}, {
  key: "control",
  label: ""
}];

const handleFinish = (status, total) => {
  totalCount.value = total || 0
  if(status) {}
  isLoading.value = false;
}

onMounted(() => {
  page.value = router.currentRoute.value.query.page || 1
  messages.getMessages({ page: page.value }, handleFinish)
})

watch(page, (newValue, oldValue) => {
  messages.getMessages({ page: newValue, limit: pageCount }, handleFinish)
});

const handleDelete = () => {
  let items = selected.value.map(item => item.id)
  messages.deleteMessages({ items }, (status, result) => {
    if(!status) {
      console.log(result)
    } else {
      messages.getMessages({ page: page.value }, handleFinish)
      toast.add({ title: 'Delete Success!' })
    }
  })
}

</script>

<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'Subscribe to updates' }]"
    class="mb-5"
  />

  <UDivider class="mb-7" />

  <UCard :ui="{ body: { base: 'grid grid-cols-1' } }">
    <div class="flex px-3 py-3.5 border-b border-gray-200 dark:border-gray-700">
<!--      <UInput v-model="q" placeholder="Filter..." />-->

      <UButton
        class="ml-auto"
        icon="i-heroicons-trash"
        size="sm"
        color="red"
        variant="solid"
        label="Delete"
        @click="handleDelete"
      />
    </div>

    <UTable
      v-model="selected"
      :rows="messages.list"
      :columns="columns"
      :loading="isLoading"
      :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
      :progress="{ color: 'primary', animation: 'carousel' }"
    >
      <template #created_at-data="{ row }">
        {{ moment(row.created_at).fromNow() }}
      </template>
      <template #email-data="{ row }">
        <ULink
          :to="'mailto:' + row.email"
          active-class="text-primary"
        >
          <span v-html="useSearch(row.email, q)"></span>
        </ULink>
      </template>
      <template #description-data="{ row }">
        <span v-html="useSearch(row.description, q)"></span>
      </template>
    </UTable>

    <div class="flex justify-end px-3 py-3.5 border-t border-gray-200 dark:border-gray-700">
      <UPagination v-model="page" :page-count="pageCount" :total="totalCount" />
    </div>
  </UCard>
</template>
