<script lang="ts" setup>
import moment from "moment";
import { useReviewStore } from "~/stores/review";

const reviews = useReviewStore()
const router = useRouter()
const isLoading = ref(true)
const page = ref(1)
const pageCount = 5
const q = ref('')
const totalCount = ref(0)
const selected = ref([])
const toast = useToast()

const columns = [{
  key: 'review_types',
  label: 'Type'
}, {
  key: 'name',
  label: 'Name'
}, {
  key: 'position',
  label: 'Position, Company'
}, {
  key: 'photo',
  label: 'Photo'
}, {
  key: 'review',
  label: 'Review / Link'
}, {
  key: 'created_at',
  label: 'Create Date'
}, {
  key: "control",
  label: ""
}]

onMounted(() => {
  page.value = router.currentRoute.value.query.page || 1
  reviews.getReviews({ page: page.value }, handleFinish)
})

watch(page, (newValue, oldValue) => {
  reviews.getReviews({ page: newValue, limit: pageCount }, handleFinish)
});

const handleFinish = (status, total) => {
  totalCount.value = total || 0
  if(status) {}
  isLoading.value = false;
}

const handleEdit = (id) => {
  router.push({
    path: "/admin/reviews/edit",
    query: {
      id: id,
      edit: true
    }
  })
}

const handleDelete = (id) => {
  reviews.deleteReview(id, ({ data }) => {
    console.log(data)
    if(!data.ok) {
      console.log(data.messages)
    } else {
      reviews.getReviews({ page: page.value }, handleFinish)
      toast.add({ title: 'Delete Success!' })
    }
  })
}
</script>

<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'Customer Reviews' }]"
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
        to="/admin/reviews/create"
        :trailing="false"
      />
    </div>

    <UTable :rows="reviews.list"
      :columns="columns"
      :loading="isLoading"
      :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
      :progress="{ color: 'primary', animation: 'carousel' }"
    >
      <template #review_types-data="{ row }">
        {{ row.review_types == 'video' ? 'Video' : 'Text' }}
      </template>
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
