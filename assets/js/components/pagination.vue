<template>
  <div class="pagination">
      <div class="pagination__left">
        <a href="#" v-if="hasPrev()" @click.prevent="changePage(prevPage)">Prev</a>
      </div>
      <div class="pagination__mid">
        <ul>
          <li v-if="hasFirst()"><a href="#" @click.prevent="changePage(1)">1</a></li>
          <li v-if="hasFirst()">...</li>
          <li v-for="page in pages">
            <a href="#" @click.prevent="changePage(page)" :class="{ current: current == page }">{{page}}</a>
          </li>
          <li v-if="hasLast()">...</li>
          <li v-if="hasLast()"><a href="#" @click.prevent="changePage(totalPages)">{{ totalPages }}</a></li>
        </ul>
      </div>
      <div class="pagination__right">
        <a href="#" v-if="hasNext()" @click.prevent="changePage(nextPage)">Next</a>
      </div>
  </div>
</template>

<script>

export default {
  name: 'pagination',

   props: {
    current: {
      type: Number,
      default: 1
    },

    total: {
      type: Number,
      default: 0
    },

    perPage: {
      type: Number,
      default: 9
    },

    pageRange: {
      type: Number,
      default: 2
    }
  },
  
computed: {

    pages() {
      var pages = []
      for(var i = this.rangeStart; i <= this.rangeEnd; i++) {
        pages.push(i)
      }
      return pages
    },

    rangeStart() {
      var start = this.current - this.pageRange
      return (start > 0) ? start : 1
    },

    rangeEnd() {
      var end = this.current + this.pageRange
      return (end < this.totalPages) ? end : this.totalPages
    },

    totalPages() {
      return Math.ceil(this.total/this.perPage)
    },

    nextPage() {
      return this.current + 1
    },

    prevPage() {
      return this.current - 1
    }
  },

  methods: {

    hasFirst() {
      return this.rangeStart !== 1
    },

    hasLast() {
      return this.rangeEnd < this.totalPages
    },

    hasPrev() {
      return this.current > 1
    },

    hasNext() {
      return this.current < this.totalPages
    },

    changePage(page) {
      this.$emit('page-changed', page)
    }
  }
};
</script>

<style scoped>

</style>
