<template>
    <div>
        <div class="navbar">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'home'}">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{name:'about'}">About</router-link>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>