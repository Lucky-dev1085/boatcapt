<template>
    <section class="section-background section-faq">
        <p class="page-title mb-5">About Us</p>

        <article class="article-modal">
            <h2 class="article-title">Our Captain, Your Boat!</h2>
            <h5 class="article-subtitle mb-5">Whether it's a sunset cocktail cruise, fishing, transport, or just becoming familiar with your local waters we’re here to help. You pick the trip and that date, we’ll instantly match you with local Captains who can make your day a much more enjoyable one.</h5>
            <div class="row">
                <div class="col-sm-12 text-center pr-0">
                    <img :src="URL.base + '/public/images/about.png'" style="margin-bottom:40px;" />
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-12 pl-sm-3 pr-0">
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Charter</strong> i.e. You’d like to take some friends out on the boat and enjoy their company without worrying about the hassles of running a boat. The Captain will take you where you want to go and get you home. 
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Fishing</strong> i.e. You want to learn more about fishing, or want to hire a captain to run the boat for you for the day so you can enjoy fishing and your guests.
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Booze Cruise</strong> i.e. Who doesn't love a beautiful sunset on the water with good friends. Let’s be responsible and hire a captain so we can enjoy ourselves!
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Delivery/Transport</strong> i.e. Are you a Marina/Dealer/Broker who needs a boat moved from port A to port B? Hire a Captain to do it for you.
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Boat Trips</strong> i.e. Have you ever wanted to take that big trip, but you weren’t confident in leaving your boating comfort zone? Hire a Captain to go with you.
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Boat Moves</strong> i.e. Want to get your boat over to the beach on a Thursday and secure the spot for the weekend? When you just don't have the time,  hire a Captain to get the boat over for you. Heck you can even throw the family on there and meet them over at the beach when you’re ready!
                    </h5>
                    <h5 class="mb-4">
                        <img :src="URL.base + '/public/images/anchor.png'" />&ensp;<strong>Special Occasion</strong> i.e Bachelorette/Bachelor Party/Anniversary we’ll run the boat you just enjoy. Maybe the wife wants a girls night out, book a captain for her and her friends for the evening.
                    </h5>
                </div>

            </div>
            <br/>
            <br/>
            <div class="text-center">
                <a :href="URL.base + '/register'" class="btn btn-darkblue btn-size-360">Create Free Account</a>
            </div>
        </article>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                URL: window.URL
            }
        },
        mounted() {            
        },

        created() {
        },
    }
</script>
