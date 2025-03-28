/*
 * Copyright (c) 2024. Khutaba Kiazim. All rights reserved.
 */
<template>
  <div class="slider" v-touch:swipe.left="prevSlide" v-touch:swipe.right="nextSlide">
    <div class="slider-container" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
      <div v-for="(image, index) in images" :key="index" class="slider-item overflow-y-auto">
        <img :src="upload_path(image.generated_name)" alt="Images slider" class="img-fluid object-fit-contain" />
      </div>
    </div>
    <template v-if="false">
      <button class="prev-btn">Назад</button>
      <button class="next-btn">Вперед</button>
    </template>
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      required: true,
      default: []
    }
  },
  data() {
    return {
      currentIndex: 0,
      direction: 1
    };
  },


  methods: {
    nextSlide() {
      if (this.currentIndex === this.images.length - 1) {
        this.direction = -1;
      }
      this.currentIndex = (this.currentIndex + this.direction + this.images.length) % this.images.length;
    },
    prevSlide() {
      if (this.currentIndex === 0) {
        this.direction = 1;
      }
      this.currentIndex = (this.currentIndex + this.direction + this.images.length) % this.images.length;
    }
  }
};
</script>

<style>
.slider {
  position: relative;
  width: 100%;
  overflow-x: hidden;
}

.slider-container {
  display: flex;
  transition: transform 0.5s ease;
}

.slider-item {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 100%;
}

.slider-item img {
  width: 300px;
}

.prev-btn, .next-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  padding: 10px;
  cursor: pointer;
}

.prev-btn {
  left: 10px;
}

.next-btn {
  right: 10px;
}
</style>
