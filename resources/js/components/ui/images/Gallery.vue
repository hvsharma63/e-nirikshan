<script setup lang="ts">
import { ref } from 'vue';
import { Eye } from 'lucide-vue-next';
import VueEasyLightbox from 'vue-easy-lightbox';

interface Props {
    images: Array<{ url: string }>;
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Attached Images'
});

const lightboxVisible = ref(false);
const lightboxIndex = ref(0);
const lightboxImages = ref<string[]>([]);

const showImage = (images: string[], idx: number) => {
    lightboxImages.value = images;
    lightboxIndex.value = idx;
    lightboxVisible.value = true;
};

const handleHide = () => {
    lightboxVisible.value = false;
};
</script>

<template>
    <div v-if="images?.length" class="mt-4">
        <h5 class="text-sm text-gray-500 mb-2">{{ title }}</h5>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div v-for="(image, idx) in images" :key="idx" class="relative group aspect-square">
                <img :src="image.url" class="w-full h-full object-cover rounded-lg" alt="Gallery image">
                <div
                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                    <button @click="showImage(images.map(img => img.url), idx)"
                        class="p-2 bg-blue-500/90 hover:bg-blue-600 text-white rounded-full transition-colors">
                        <Eye class="h-4 w-4 sm:h-5 sm:w-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <vue-easy-lightbox :visible="lightboxVisible" :imgs="lightboxImages" :index="lightboxIndex" @hide="handleHide" />
</template>
