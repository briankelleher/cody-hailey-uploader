<template>
    <div class="card mb-3">
        <div class="row g-0">
            <div class="col-5">
            <img :src="file.preview" class="img-fluid rounded-start" alt="...">
            </div>
            <div class="col">
            <div class="card-body">
                <p>Uploaded at {{ fileCreatedAt }}</p>
            </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps(['file'])

const fileCreatedAt = computed(() => {
    const date = new Date(props.file.created_at);
    const month = date.getMonth(); // Returns the month (0 - 11, where 0 represents January)
    const day = date.getDate(); // Returns the day of the month (1 - 31)
    let hour = date.getHours(); // Returns the hour (0 - 23)
    const minutes = date.getMinutes(); // Returns the minutes (0 - 59)
    let amOrPm = 'AM'; // By default, AM is assumed
    if ( hour > 11 ) {
        // If hour is greater than 11, then change amOrPm to 'PM'
        amOrPm = 'PM';
        hour = hour === 12 ? hour : hour - 12;
    }
    return `${month + 1}/${day} ${hour}:${minutes} ${amOrPm}`;
});
</script>