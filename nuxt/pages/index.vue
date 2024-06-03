<script setup lang="ts">
import { ModalDemo } from '#components'

const modal = useModal();
const router = useRouter();
const auth = useAuthStore();

function openDemoModal() {
  modal.open(ModalDemo)
}
</script>

<template>
  <div class="grid grid-cols-12 gap-6" />
</template>
