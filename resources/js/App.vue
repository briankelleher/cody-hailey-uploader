<template>
    <div class="main-app">
        <section class="px-5 py-6 hcw-hero text-white">
            <div class="container py-5">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Thanks for coming!</h1>
                        <h4>Hailey &amp; Cody's Wedding Photo Drive</h4>
                        <p>Submit your favorite photos of you and the couple or any photos from the engagement party. We plan to use these photos in the wedding slide show so keep an eye out for your submissions!</p>
                        <file-uploader></file-uploader>
                    </div>
                </div>
            </div>
        </section>
        <div class="container py-5">
            <div class="file-previews">
                <p>{{ filePreviewsLabel }}</p>

                <div class="row">
                    <div class="col-12 col-md-6 mb-3" v-for="file in store.uploadedFiles" :key="file.name">
                        <file-preview :file="file"></file-preview>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import FileUploader from './components/FileUploader.vue';
import FilePreview from './components/FilePreview.vue';
import { store } from './store.js';
import { computed } from 'vue';

const filePreviewsLabel = computed(() => {
  if (store.uploadedFiles.length === 0) {
    return 'No photos added.';
  } else if (store.uploadedFiles.length === 1) {
    return '1 photo added!';
  } else {
    return `${store.uploadedFiles.length} photos added!`;
  }
})
</script>