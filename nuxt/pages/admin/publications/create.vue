<script lang="ts" setup>
const router = useRouter();
const dayjs = useDayjs();
const auth = useAuthStore();
const isEdit = ref(false);
const publicationId = ref(0);
const publications = usePublicationStore();
const toast = useToast()

const error = reactive({})
const description = ref("")
const platforms = [
  { name: 'VC', value: 'vc' },
  { name: 'Habr', value: 'habr' },
  { name: 'YouTube', value: 'youtube' }
];

const state = reactive({
  title: "",
  date: new Date(),
  description: "",
  image: "https://",
  platform: 'vc',
  link: "https://",
  metrics: {
    views: null,
    likes: null,
    comments: null,
    favorites: null
  }
});

onMounted(() => {
  let { id, edit } = router.currentRoute.value.query;
  isEdit.value = edit || false
  publicationId.value = id || 0
  if(id)
    publications.getPublication(id, (data) => {
      state.title = data.title
      description.value = data.description
      state.date = new Date(data.date)
      state.link = data.link
      state.location = data.location
    })
})

const getDateTime = (date) => {
  let year = date.getFullYear();
  let month = String(date.getMonth() + 1).padStart(2, '0'); // Adding 1 to month because it is zero-based
  let day = String(date.getDate()).padStart(2, '0');

  return `${year}-${month}-${day}`;
}

const handleSubmit = (e) => {
  if(!isEdit.value) {
    publications.createPublication({ ...state,
      pub_date: getDateTime(state.date),
      favs: state.metrics.favorites,
      likes: state.metrics.likes,
      views: state.metrics.views,
      comments: state.metrics.comments,
      description: description.value
    }, ({ data }) => {
      console.log(data)
      if(data.ok == true) {
        toast.add({ title: 'Create publication success!', color: 'primary' })
        state.title = ""
        state.platform = "vc"
        state.link = "https://"
        description.value = ""
        state.metrics.favorites = null
        state.metrics.likes = null
        state.metrics.views = null
        state.metrics.comments = null
        state.date = new Date()
        state.image = "https://"
      } else {
        toast.add({ title: 'Create publication failed!', description: data.message, color: 'red' })
      }
    })
  } else {
    publications.updatePublication(
      publicationId.value,
      { ...state, date: getDateTime(state.date), description: description.value},
      (data) => console.log(data)
    )
    toast.add({ title: 'Hello world!' })
  }
};

const handleBack = (e) => {
  router.back();
};
</script>
<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'My Publications', to: '/admin/publications' }, { label: isEdit ? 'Edit' : 'Create' } ]"
    class="mb-5"
  />

  <UDivider class="mb-7" />

  <UCard :ui="{ body: { base: 'grid grid-cols-1' } }">
    <UForm :state="state" class="space-y-4">
      <UFormGroup label="Title" name="title" eager-validation required>
        <UInput v-model="state.title" placeholder="" />
      </UFormGroup>

      <UFormGroup label="Date" name="date" eager-validation>
        <VueDatePicker
          v-model="state.date"
          text-input
          format="MM/dd/yyyy"
        />
      </UFormGroup>

      <UFormGroup label="Description" name="description" eager-validation>
        <UTextarea v-model="description" :rows="3" />
      </UFormGroup>

      <UFormGroup label="Image" name="image" eager-validation>
        <UInput size="sm" icon="i-heroicons-link" v-model="state.image"/>
      </UFormGroup>

      <UFormGroup label="Platform" name="platform" eager-validation>
        <USelect v-model="state.platform" :options="platforms" name="platform" option-attribute="name"/>
      </UFormGroup>

      <UFormGroup label="Link" name="link" eager-validation :error="error.link && 'You must enter an email'">
        <UInput v-model="state.link" placeholder="" />
      </UFormGroup>

      <UFormGroup label="Metrics" name="metrics" eager-validation>
        <div class="grid grid-cols-4 gap-2">
          <UInput v-model="state.metrics.views"
            icon="i-heroicons-eye"
            placeholder="Views"
            name=""
          />
          <UInput v-model="state.metrics.likes"
            icon="i-heroicons-hand-thumb-up"
            placeholder="Likes"
            name="likes"
          />
          <UInput v-model="state.metrics.comments"
            icon="i-heroicons-chat-bubble-oval-left"
            placeholder="Comments"
            name="comments"
          />
          <UInput v-model="state.metrics.favorites"
            icon="i-heroicons-heart"
            placeholder="Favorites"
            name="favourites"
          />
        </div>
      </UFormGroup>

      <UButton
        type="submit"
        color="primary"
        @click="handleSubmit"
        class="mr-3"
        :label="!isEdit ? 'Submit' : 'Update'"
      />
      <UButton type="button" variant="outline" @click="handleBack" label="Back" />
    </UForm>
  </UCard>
</template>
