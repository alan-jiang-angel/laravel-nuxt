<script lang="ts" setup>
const router = useRouter();
const dayjs = useDayjs();
const auth = useAuthStore();
const isEdit = ref(false);
const eventId = ref(0);
const events = useEventStore();
const toast = useToast()

const error = reactive({})
const description = ref("")
const state = reactive({
  title: "",
  date_time: new Date(),
  link: "https://",
  location: undefined,
});

onMounted(() => {
  let { id, edit } = router.currentRoute.value.query;
  isEdit.value = edit || false
  eventId.value = id || 0
  if(id)
    events.getEvent(id, (data) => {
      state.title = data.title
      description.value = data.description
      state.date_time = new Date(data.date_time)
      state.link = data.link
      state.location = data.location
    })
})

const getDateTime = (date) => {
  let year = date.getFullYear();
  let month = String(date.getMonth() + 1).padStart(2, '0'); // Adding 1 to month because it is zero-based
  let day = String(date.getDate()).padStart(2, '0');
  let hour = String(date.getHours()).padStart(2, '0');
  let minute = String(date.getMinutes()).padStart(2, '0');
  let second = String(date.getSeconds()).padStart(2, '0');

  return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
}

const handleSubmit = (e) => {
  if(!isEdit.value) {
    events.createEvent({ ...state, date_time: getDateTime(state.date_time), description: description.value },
      ({ data }) => {
        if(data.ok) {
          toast.add({ title: 'Create Success!' })
        } else {
          toast.add({ title: 'Create event failed!', description: data.message, color: 'red' })
        }
      })
  } else {
    events.updateEvent(
      eventId.value,
      { ...state, date_time: getDateTime(state.date_time), description: description.value },
      ({ data }) => {
        if(data.ok) {
          toast.add({ title: 'Update Success!' })
        } else {
          toast.add({ title: 'Update event failed!', description: data.message, color: 'red' })
        }
      }
    )

  }
};

const handleBack = (e) => {
  router.back();
};
</script>
<template>
  <UBreadcrumb
    divider="/"
    :links="[{ label: 'Home', to: '/' }, { label: 'Events', to: '/admin/events' }, { label: isEdit ? 'Edit' : 'Create' } ]"
    class="mb-5"
  />

  <UDivider class="mb-7" />

  <UCard :ui="{ body: { base: 'grid grid-cols-1' } }">
    <UForm :state="state" class="space-y-4">
      <UFormGroup label="Title" name="title" eager-validation required>
        <UInput v-model="state.title" placeholder="" />
      </UFormGroup>
      <UFormGroup label="Description" name="description" eager-validation>
        <UTextarea v-model="description" :rows="3" />
      </UFormGroup>
      <UFormGroup label="Date and time" name="date_time" eager-validation>
        <VueDatePicker
          v-model="state.date_time"
          text-input
          format="MM/dd/yyyy HH:mm:ss"
        />
      </UFormGroup>
      <UFormGroup label="Link" name="link" eager-validation :error="error.link && 'You must enter an email'">
        <UInput v-model="state.link" placeholder="" />
      </UFormGroup>
      <UFormGroup label="Location" name="location" eager-validation>
        <UInput v-model="state.location" placeholder="" />
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
