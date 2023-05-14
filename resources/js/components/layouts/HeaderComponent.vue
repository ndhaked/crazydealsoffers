<template>
     <header>
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
          <a class="navbar-brand main-logo" :href="baseUrl">
            <img :src="baseUrl+'/front/images/logo.svg'" alt="" :title="APP_NAME">
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" aria-current="page" :href="baseUrl">Home</a>
              </li>
              <li class="nav-item">
              
                <a class="nav-link" href="javascript:void(0);">How it works</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" :href="FAQS">FAQs</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" :href="BLOG">Blogs</a>
              </li>
            </ul>
            <div id="wrap" class="header-search-panel">
              <form action="" autocomplete="on">
                  <input id="search" name="search" type="text" placeholder="Search">
                  <input id="search_submit" value="Rechercher" type="submit">
              </form>
          </div>
          </div>
        </div>
      </nav>
    </header>
</template>
<script>
    export default {
          data() {
            return {
               baseUrl: APP_URL,
               APP_NAME: APP_NAME,
               FAQS:FAQS,
               BLOG:BLOG,
            }
          },
        mounted() {
        }      
    }
</script>