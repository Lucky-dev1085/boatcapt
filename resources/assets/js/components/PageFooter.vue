<template>
    <footer>
        <p><img :src="URL.base + '/public/images/footer-logo.png'" /></p>
        <p class="footer-content">The Capt App, LLC            
            <br/> (631) 994-1234
            <br /> <a href="mailto:john@thecaptapp.com">Email John</a>
        </p>            
        <p class="footer-link">
            <a href="#" class="fa fa-facebook-square"></a>
            <a href="#" class="fa fa-twitter"></a>
            <a href="#" class="fa fa-instagram"></a>
        </p>
    </footer>
</template>

<script>
    export default {

        data() {
            return {
                URL: window.URL,
            }
        },

        mounted() {   
            $(".btn-toggle-menu,.aside-overlay").click(function(){
                $("body").toggleClass("menu");
            })         
        }
    }
</script>
